<?
class Registrations extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('json');
        $this->load->model('Registration');
    }

    public function counselors_get()
    {
        $counselors = $this->User->get_list(999, 0, 0, null, USER_TYPE_COUNSELOR);
        $updated = array();
        foreach ($counselors as $counselor) {
            // Don't list Kali
            if ($counselor->username != 'kali') {
                $simple = new stdClass;
                $simple->name = $counselor->firstname . " " . $counselor->lastname;
                $updated[] = $simple;
            }
        }

        $this->response($updated);
    }

    /**
     * Returns a single user -- either the logged-in user if the id = 0 or the user for the id
     * @param int $id The id of the user
     */
    public function registration_get()
    {
        $registration_id = intval($this->session->userdata('registration_id'));
        $registration = $this->Registration->load($registration_id);

        if ($registration) {

            /* Don't allow them to continue a completed registration */
            if ($registration->completed > 0) {
                $registration = $this->Registration->blank();
            }

            $data = json_decode($registration->data);
            $registration = array_merge((array)$data, (array)$registration);

            /* remove the password */
            unset($registration['password']);
            unset($registration['salt']);
            unset($registration['data']);
        }

        $this->response($registration);
    }

    private function registration_purchase($user)
    {

        \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));

        $token = $this->put('token');
        $chosen_plan_id = $this->put('chosen_plan_id');

        try {
            $customer = \Stripe\Customer::create(array(
                "source" => $token,
                "email" => $user->email,
                "description" => 'Username: ' . $user->username
            ));
        } catch (Exception $e) {
            loggly(array(
                'text' => $e->getMessage(),
                'method' => 'rest.registrations.registration_purchase',
                'exception' => $e,
                'actor_id' => get_user_id()
            ));
            /* Remove the bad user from the database */
            $this->User->delete_bad_registration($user->id);
            log_message('error', sprintf('[New Registration] Customer Creation Failed [%s] ', $e->getMessage()));
            json_error("There was a problem processing your payment, please try again. The following error was returned from the gateway: " . $e->getMessage());
            loggly(array(
                'error' => "There was a problem processing your payment, please try again. The following error was returned from the gateway: " . $e->getMessage(),
                'method' => 'registrations.registration_purchase',
                'token' => $token,
                'chosen_plan_id' => $chosen_plan_id,
                'user' => $user
            ));
            return -1;
        }

        log_message('info', sprintf('4 [New Registration] Customer [%s] ', $customer->id));

        $this->User->set_stripe_data($user->id, $customer);

        /** This is a subscription */
        if (intval($chosen_plan_id)) {
            $this->load->model('Plan');
            $chosen_plan_id = intval($chosen_plan_id);
            $plan = $this->Plan->load($chosen_plan_id);

            $subscription_data = array("plan" => $plan->stripe_plan_id);
            $code = $this->put('code');

            if ($code) {
                $subscription_data['coupon'] = $code;
            }
            try {
                $plan_data = $customer->subscriptions->create($subscription_data);
                log_message('info', sprintf('5 [New Registration] Blush Plan[%d], Stripe Plan ID [%s] ', $chosen_plan_id, $plan->id));
            } catch (Exception $e) {
                loggly(array(
                    'text' => $e->getMessage(),
                    'method' => 'rest.registrations.registration_purchase',
                    'exception' => $e,
                    'actor_id' => get_user_id()
                ));
                /* Remove the bad user from the database */
                $this->User->delete_bad_registration($user->id);
                log_message('error', sprintf('[New Registration] Subscription Failed [%s] ', $e->getMessage()));
                json_error("There was a problem processing your payment, please try again. The following error was returned from the gateway: " . $e->getMessage());
                loggly(array(
                    'error' => "There was a problem processing your payment, please try again. The following error was returned from the gateway: " . $e->getMessage(),
                    'method' => 'registrations.registration_purchase',
                    'plan_data' => $plan_data,
                    'chosen_plan_id' => $chosen_plan_id,
                    'user' => $user,
                    'code' => $code
                ));
                return -1;
            }

            /** We don't add credits yet...we let the stripe hook do that when we verify payment */
            $this->User->update($user->id, array(
                    'plan_id' => $plan->id,
                    'stripe_subscription_id' => $plan_data['id'],
                    'stripe_subscription_data' => serialize($plan_data))
            );
            /* Just return 1 since we haven't actually added the transaction yet */
            return 1;

        } else {
            if ($chosen_plan_id == 'video') {
                $amount = get_price_video();
                $diary_cnt = 0;
                $counseling_cnt = 1;
                $credits = CREDITS_COUNSELING;
            } else {
                $amount = get_price_journal();
                $diary_cnt = 1;
                $counseling_cnt = 0;
                $credits = CREDITS_DIARY;
            }
            $charge = \Stripe\Charge::create(array(
                "amount" => ($amount * 100),
                "customer" => $customer->id,
                "currency" => "usd",
                "description" => sprintf('Charge for purchase of initial session')
            ));
            log_message('info', sprintf('5 [New Registration] A la Carte [%s], Stripe Charge ID [%s] ', $chosen_plan_id, $charge->id));

            $data = array(
                'customer_id' => $user->id,
                'amount' => $amount,
                'stripe_id' => $charge->id,
                'stripe_data' => serialize($charge),
                'diary_cnt' => $diary_cnt,
                'counseling_cnt' => $counseling_cnt
            );
            $transaction_specific_data = $this->Transaction->add_data();
            $data = array_merge($data,$transaction_specific_data);
            $transaction_id = $this->Transaction->add($data);
            if (!$transaction_id) {
                log_message('error', '[New Registration] A la cart purchase failed database insert');
                json_error("There was a problem processing your payment, please try again.");
                loggly(array(
                    'error' => 'There was a problem processing your payment, please try again',
                    'method' => 'registrations.registration_purchase',
                    'data' => $data,
                    'charge' => $charge
                ));
                return -1;
            }
            $this->User->update($user->id, array('credits' => $credits));
            return $transaction_id;
        }
    }

    public function registration_put($uuid = 0)
    {
        $this->load->model('Transaction');

        $data = $this->get_put_fields($this->Registration->get_fields());
        $data_data = $this->get_put_fields($this->Registration->get_data_fields());
        log_message('info', sprintf('0 [New Registration] Registration PUT Data %s', print_r($data_data, TRUE)));
        $data['data'] = json_encode($data_data);

        $this->Registration->update_by_uuid($uuid, $data);
        $return_data = array('uuid' => $uuid);

        /* If this is the final step, create the user account */
        if (intval($this->put('completed')) === 1) {

            $registration = $this->Registration->load_by_uuid($uuid);
            if(!$registration) {
                log_message('error', sprintf("Registration was null during Registration for uuid %s", $uuid));
                json_error('There was a problem with your registration. Please email us at info@joinblush.com');
                loggly(array(
                    'error' => 'There was a problem with your registration. Please email us at info@joinblush.com',
                    'method' => 'registrations.registration_put',
                    'uuid' => $uuid,
                    'data_data' => $data_data
                ));
                exit;
            }

            log_message('info', sprintf('1 [New Registration] Registration [%s] - %s ', $uuid, print_r($registration, TRUE)));

            if(!$registration->user_id) {
                $user_id = $this->User->create_from_registration($registration);
            } else {
                $user_id = $registration->user_id;
            }

            //If the user id is null, look up any user who hasn't been welcomed who has the email address
            if (!$user_id) {
                log_message('error', "Couldn't create user from registration...looking up existing user with email");
                $user_id = $this->User->load_by_email_unwelcomed($registration->email);
                if($user_id) {
                    log_message('error', "Found existing user: ".$user_id);
                }
            }

            if (!$user_id) {
                log_message('error', "User ID was null during Registration");
                json_error('There was a problem with your registration. Please email us at info@joinblush.com');
                exit;
            } else {
                $this->Registration->update($registration->id, array('user_id' => $user_id, 'completed' => 1));
                $user = $this->User->load($user_id);
                log_message('info', sprintf('2 [New Registration] User [%s] ', print_r($user, TRUE)));
                log_message('info', sprintf('3 [New Registration] Token [%s] ', $this->put('token')));

                /** They are paying for their subscription, alacarte */
                if ($this->put('token')) {
                    $transaction_id = $this->registration_purchase($user);

                    /* If the transaction failed, delete the user and let them try again */
                    if (!$transaction_id || $transaction_id < 0) {
                        $this->User->delete_bad_registration($user->id);
                        log_message('error', "Registration was bad, deleting registration");
                        json_error('There was a problem with your registration');
                        exit;
                    }
                    if ($transaction_id > 1) {
                        $transaction = $this->Transaction->load($transaction_id);
                        $return_data['transaction'] = $transaction;
                        $return_data['transaction_id'] = $transaction_id;
                    }
                } else {
                    log_message('error', sprintf('[New Registration] No Token Supplied ', print_r($user, TRUE)));
                    $this->User->delete_bad_registration($user->id);
                    log_message('error', "Registration was bad, deleting registration");
                    json_error('You must select either a plan or one a la carte item when registering');
                    exit;
                }

                $this->load->helper('notification');
                notify_new_user($user_id);
                if ($user->inactive) {
                    $return_data['inactive'] = 1;

                    /* Create the registration key and store it on the user so they can validate */
                    $confirmation_key = random_string('unique');
                    $this->User->update($user_id, array('confirmation_key' => $confirmation_key));

                    notify_parent_approval($user_id, $confirmation_key);
                    $this->session->set_flashdata('info', 'Your account is currently awaiting parental approval.  Once we have received approval from your parents, your account will become active.');
                } else {
                    $this->session->set_userdata('user_id', $user_id);
                }

                $return_data['registration_id'] = $registration->id;
                $return_data['user'] = decorate_user($user, true);
                log_message('info', sprintf('6 [New Registration] Registration Complete'));
                json_success('Registration has been updated successfully', $return_data);
                exit;
            }
        }
        json_success('The registration you created has been updated successfully.', $return_data);
    }

    /**
     * Returns a single user -- either the logged-in user if the id = 0 or the user for the id
     * @param int $id The id of the user
     */
    public function user_get()
    {
        $registration = $this->Registration->load_by_user_id(get_user_id());

        if ($registration) {

            $data = json_decode($registration->data);
            $registration = array_merge((array)$data, (array)$registration);

            /* remove the password */
            unset($registration['password']);
            unset($registration['salt']);
            unset($registration['data']);
        }

        $this->response($registration);
    }

    public function user_put()
    {
        $registration = $this->Registration->load_by_user_id(get_user_id());

        $data = $this->get_put_fields($this->Registration->get_fields());
        $data_data = $this->get_put_fields($this->Registration->get_data_fields());
        $data['data'] = json_encode($data_data);

        $this->Registration->update($registration->id, $data);

        $return_data = array('uuid' => $registration->uuid);
        json_success('Registration has been updated successfully', $return_data);
    }

    public function registration_post($uuid = 0)
    {
        $data = $this->get_post_fields($this->Registration->get_fields());
        $data_data = $this->get_post_fields($this->Registration->get_data_fields());
        $data['data'] = json_encode($data_data);

        $id = $this->Registration->add($data);
        $this->session->set_userdata('registration_id', $id);
        json_success('The registration you created has been saved successfully.', array('uuid' => $this->Registration->get_uuid($id)));
    }
}

?>