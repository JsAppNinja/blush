<?
class Users extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->validate_user();
        $this->load->helper('json');
        $this->load->model(array('User', 'Registration', 'Message', 'Diary', 'Plan', 'Event'));
    }

    public function session_get()
    {
        $this->response(array('session_valid' => (get_user_id() > 0)));
    }

    /**
     * Returns a single user -- either the logged-in user if the id = 0 or the user for the id
     * @param int $id The id of the user
     */
    public function user_get()
    {

        $user = get_user();
        $user = decorate_user($user, true);
        $this->response($user);
    }

    public function availability_calendar_get() {
        $start = $this->get('start');
        $end = $this->get('end');

        $this->load->model('Availability_Calendar');
        $user = get_user();
        if ($user->user_type_id == USER_TYPE_COUNSELOR) {
            $availability_calendar_items = $this->Availability_Calendar->get_list(999, 0, NULL, NULL, $user->id, $start, $end);
            $this->response(decorate_availability_calendars($availability_calendar_items, $user));
        }
    }

    public function availability_calendar_post() {
        $this->load->model('Availability_Calendar');
        $user = get_user();
        $data = $this->get_post_fields($this->Availability_Calendar->get_fields());
        $data['user_id'] = $user->id;

        /* Convert the times to central */
        if ($user->timezone && !intval($data['is_all_day'])) {
            $startdatetime = new DateTime($data['start_time'], new DateTimeZone($user->timezone));
            $startdatetime->setTimezone(new DateTimeZone(getenv('TZ')));
            $enddatetime = new DateTime($data['end_time'], new DateTimeZone($user->timezone));
            $enddatetime->setTimezone(new DateTimeZone(getenv('TZ')));
        } else {
            $startdatetime = new DateTime($data['start_time']);
            $enddatetime = new DateTime($data['end_time']);
        }
        $data['start_time'] = $startdatetime->format('H:i:s');
        $data['end_time'] = $enddatetime->format('H:i:s');


        //echo 'Start: ' . date('F j, Y H:i:s A T', $startdatetime->getTimestamp()) . "\n";
        //echo 'End: ' . date('F j, Y H:i:s A T', $enddatetime->getTimestamp()) . "\n\n";
        $id = $this->Availability_Calendar->add($data);
        $availability_calendar = $this->Availability_Calendar->load($id);
        $this->response(decorate_availability_calendar($availability_calendar, $user));

    }

    public function availability_calendar_delete($id) {
        $this->load->model('Availability_Calendar');
        $user = get_user();
        $availability_calendar = $this->Availability_Calendar->load($id);
        if($availability_calendar) {
            if($availability_calendar->user_id == $user->id) {
                $this->Availability_Calendar->delete($id);
                json_success('Calendar Item deleted successfully.');
                exit;
            } else {
                json_error('You do not have the required privilege to perform this action.');
                exit;
            }
        }
        json_error('There was no calendar item with that id');
    }

    /**
     * Returns the availability (days/hours) for the currently logged-in coach
     */
    public function availability_get()
    {
        $this->load->model('Availability');
        $availability = NULL;
        $user = get_user();

        if ($user->user_type_id == USER_TYPE_COUNSELOR) {
            $timeslots = $this->Availability->get_by_user($user->id);
        } else {
            $counselor = get_counselor($user->id);
            if ($counselor) {
                $counselor_id = $this->User->get_id($counselor->uuid);
                $timeslots = $this->Availability->get_by_user($counselor_id);
            }
        }

        if ($timeslots) {
            $availability = new stdClass;
            $availability->timeslots = decorate_availabilities($timeslots, $user);

            $this->response($availability);
            exit;
        }

        $this->response(array());
    }

    /** Called by the My Account -> Availability page to add a new availability */
    public function availability_post()
    {
        $this->load->model('Availability');

        $user = get_user();

        $action = $this->post('action');

        if ($action === 'add') {
            if ($user->user_type_id == USER_TYPE_COUNSELOR) {
                $data = $this->get_post_fields($this->Availability->get_fields());
                $data['user_id'] = $user->id;

                /* Convert the times to central */
                if ($user->timezone) {
                    //date_default_timezone_set($user->timezone);
                    $startdatetime = new DateTime($data['start_time'], new DateTimeZone($user->timezone));
                    $startdatetime->setTimezone(new DateTimeZone(getenv('TZ')));
                    $enddatetime = new DateTime($data['end_time'], new DateTimeZone($user->timezone));
                    $enddatetime->setTimezone(new DateTimeZone(getenv('TZ')));
                } else {
                    $startdatetime = new DateTime($data['start_time']);
                    $enddatetime = new DateTime($data['end_time']);
                }

                $data['start_time'] = $startdatetime->format('H:i:s');
                $data['end_time'] = $enddatetime->format('H:i:s');


                //echo 'Start: ' . date('F j, Y H:i:s A T', $startdatetime->getTimestamp()) . "\n";
                //echo 'End: ' . date('F j, Y H:i:s A T', $enddatetime->getTimestamp()) . "\n\n";
                $id = $this->Availability->add($data);
                $availability = $this->Availability->load($id);
                $this->response(decorate_availability($availability, $user));
            }
        } else {
            $this->Availability->delete($this->post('id'), $user->id);
            json_success("Availability deleted successfully.");
        }
    }

    public function customer_get($uuid = '')
    {
        $user = $this->User->load_by_uuid($uuid);

        if ($user) {
            $registration = $this->Registration->load_by_user_id($user->id);
            if ($registration) {
                $registration->data = json_decode($registration->data);
                $user->registration = $registration;
            } else {
                $user->registration = new stdClass;
                $user->registration->data = '';
            }
        }
        $user = $this->decorate_object($user);

        $this->response($user);
    }

    public function user_put()
    {
        $user = get_user();
        $this->User->update_by_uuid($user->uuid, $this->get_put_fields($this->User->get_fields()));

        /** Update password? */
        if ($this->put('new_password')) {
            /** validate existing password */
            $existing = sha1(trim($this->put('existing_password')).$user->salt);
            if ($existing != $user->password) {
                json_error('The password you entered for your current password is incorrect.  You must enter your current password correctly in order to change your password.');
                return;
            } else {
                $this->User->change_password($user->id, $this->put('new_password'));
            }
        }

        // /** Agreeing to TOS */
        // if($this->put('TosAgree')){
        //     $stripe_id = $user->stripe_customer_id;
        //     $acct = \Stripe\Account::retrieve($stripe_id);
        //     $acct->tos_acceptance->date = time();
        //     $acct->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
        //     $acct->save();
        // }
        // else{
        //     json_error(strval($this->put('TosAgree')));
        // }

        /** Are they changing their plan? */
        if (intval($this->put('plan_id')) && $this->put('plan_id') != $user->plan_id) {
            $this->update_subscription($user);
        }


        /** If this is a stripe update, we have different handling */
        if ($this->put('stripe_token')) {
            if ($this->put('account_type') == 'checking') {
                $this->create_stripe_checking($user->uuid);
            } else {
                $this->create_stripe_credit($user->uuid);
            }
        } else {
            json_success('Your profile has been updated successfully!!');
        }
    }

    /**
     * Returns the welcome modal when the user should be prompted
     */
    public function welcome_get()
    {
        $user = get_user();
        if (!$user->welcomed) {
            $this->load->view('partials/dashboard/welcome');
            $this->User->update($user->id, array('welcomed' => 1));
        }
    }

    public function picture_post()
    {

        $user = get_user();
        $this->load->library(array('s3', 'uuid', 'image_moo'));

        $config['upload_path'] = $this->config->item('upload_dir');
        $config['allowed_types'] = $this->config->item('upload_types');
        $config['max_size'] = $this->config->item('max_file_upload_size');
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('picture')) {
            $data = $this->upload->data();

            //array_print($data);

            try {
                $this->User->update($user->id, array('picture' => $data['file_name']));

                $full_url = $this->create_thumbnails($data);

            } catch (Exception $e) {
                $error = array('error' => 'We experience an error while trying to upload your file.  Please try again');
                log_message('info', '[File Add] putObject Exception: '.$e->getMessage());
                json_error($error);
                return;
            }
        } else {
            return json_error($this->upload->display_errors());
        }
        return json_success("Picture Uploaded Successfully", array("picture_url" => $full_url));
    }

    public function purchase_post()
    {

        $user = get_user();

        $diary_cnt = intval($this->post('diary_cnt'));
        $counseling_cnt = intval($this->post('counseling_cnt'));
        $amount = doubleval((max($diary_cnt, 0) * get_price_journal()) + (max($counseling_cnt, 0) * get_price_video()));

        if ($amount <= 0) {
            json_error('Your purchase must be for at least one diary or counseling session');
            return;
        }

        try {

            \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));
            $charge = \Stripe\Charge::create(array(
                "amount" => ($amount * 100),
                "customer" => $user->stripe_customer_id,
                "currency" => "usd",
                "description" => sprintf('Charge for purchase of %d journals and %d coaching sessions', $diary_cnt, $counseling_cnt)
            ));

            $data = array(
                'customer_id' => $user->id,
                'amount' => $amount,
                'stripe_id' => $charge->id,
                'stripe_data' => serialize($charge),
                'diary_cnt' => $diary_cnt,
                'counseling_cnt' => $counseling_cnt
            );

            $this->load->model('Transaction');
            /** Create the transaction **/
            $transaction_id = $this->Transaction->add($data);

            /** Add the credits to the user */
            $credits = intval($user->credits + ($diary_cnt * CREDITS_DIARY) + ($counseling_cnt * CREDITS_COUNSELING));
            $this->User->update($user->id, array('credits' => $credits));

            json_success('Your purchase of '.dollarfy($amount).' was process successfully', array('credits' => $credits));

        } catch (Exception $e) {
            log_message('info', '[Credit] Stripe_Charge::create Exception: '.$e->getMessage());
            $error = sprintf('There was a problem processing your credit card. The error returned from the credit card gateway was \'%s\'', $e->getMessage());
            json_error($error);
        }
    }

    private function create_stripe_credit($uuid)
    {
        \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));
        $user = $this->User->load_by_uuid($uuid);

        $stripe_token = $this->put('stripe_token');
        if ($user) {
            $this->session->unset_userdata('stripe_customer');
            $customer = '';
            try {
                if ($user->stripe_customer_id) {
                    $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                }
            } catch (Exception $e) {
                /* Ignore it */
            }

            try {
                if ($customer) {
                    $customer->card = $stripe_token;
                    $customer->save();

                } else {
                    $customer = \Stripe\Customer::create(array(
                        "source" => $stripe_token,
                        "email" => $user->email,
                        "description" => 'Username: '.$user->username
                    ));
                }
                $this->User->set_stripe_data($user->id, $customer);
            } catch (Exception $e) {
                log_message('info', '[Credit] Stripe_Customer::create Exception: '.$e->getMessage());
                $error = sprintf('There was a problem processing your credit card. The error returned from the credit card gateway was \'%s\'', $e->getMessage());
                json_error($error);
            }
        }
        json_success('Your profile has been updated successfully.');
    }

    private function create_stripe_checking($uuid)
    {
        \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));
        $user = $this->User->load_by_uuid($uuid);

        $stripe_token = $this->put('stripe_token');
        if ($user) {
            $this->session->unset_userdata('stripe_customer');
            $customer = '';
            try {
                if ($user->stripe_customer_id) {
                    $customer = \Stripe\Account::retrieve($user->stripe_customer_id);
                }
            } catch (Exception $e) {
                log_message('info', '[Create Stripe Checking] Stripe_Customer::retrieve Exception: '.$e->getMessage());
            }

            try {
                if ($customer) {
                    $customer->bank_account = $stripe_token;
                    $customer->name = $user->firstname." ".$user->lastname;
                    $customer->save();

                } else {
                    $customer = \Stripe\Account::create(array(
//                        "bank_account" => $stripe_token,
                        "business_name" => $user->firstname." ".$user->lastname,
                        "email" => $user->email,
                        'type' => 'custom'
                    ));
                }
                $this->User->set_stripe_data($user->id, $customer);
            } catch (Exception $e) {
                log_message('info', '[Create Stripe Checking] Stripe_account::create Exception: '.$e->getMessage());
                $error = sprintf('There was a problem processing your account. The error returned from the financial gateway was \'%s\'', $e->getMessage());
                json_error($error);
            }
        }
        json_success('Your profile has been updated successfully!');
    }

    private function update_subscription($user)
    {
        $this->load->model('Plan');
        \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));
        $plan_id = $this->put('plan_id');
        log_message('info', sprintf('[update_subscription] %s is updating their subscription to plan %d.', $user->username, $plan_id));

        /* If they have chosen to cancel their plan, we will send a -1 value */
        if ($plan_id == -1) {
            log_message('info', sprintf('[update_subscription] %s is cancelling their account.', $user->username));
            $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
            log_message('info', sprintf('[update_subscription] cancelling subscription id: .', $user->stripe_subscription_id));
            if ($user->stripe_subscription_id) {
                try {
                    $subscription = $customer->subscriptions->retrieve($user->stripe_subscription_id);
                    if ($subscription) {
                        $subscription->cancel();
                    }
                } catch (Exception $e) {
                    log_message('error', sprintf('[update_subscription] Error while cancelling subscription: .', $e->getMessage()));
                    loggly(array(
                        'text' => $e->getMessage(),
                        'method' => 'rest.users.update_subscription',
                        'exception' => $e,
                        'user' => $user
                    ));
                    json_error('There was a problem cancelling your subscription. The error returned was: '.$e->getMessage());
                    exit;
                }
            }
            $update_data = array(
                'plan_id' => NULL,
                'stripe_subscription_id' => NULL,
                'stripe_subscription_data' => NULL,
            );
            $this->User->update($user->id, $update_data);
        } else {
            /* Otherwise they are changing their plan */
            $plan = $this->Plan->load($this->put('plan_id'));

            try {
                $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                if ($user->stripe_subscription_id) {
                    $subscription = $customer->subscriptions->retrieve($user->stripe_subscription_id);
                    $subscription->plan = $plan->stripe_plan_id;
                    $subscription->prorate = false;
                    $plan_data = $subscription->save();
                } else {
                    /* This is a new subscription */
                    $subscription_data = array("plan" => $plan->stripe_plan_id);
                    $plan_data = $customer->subscriptions->create($subscription_data);
                }
            } catch (Exception $e) {
                log_message('error', sprintf('[update_subscription] Error while updating subscription: .', $e->getMessage()));
                loggly(array(
                    'text' => $e->getMessage(),
                    'method' => 'rest.users.update_subscription',
                    'exception' => $e,
                    'user' => $user
                ));
                json_error('There was a problem updating your subscription. The error returned was: '.$e->getMessage());
                exit;
            }

            if ($plan_data) {
                $update_data = array(
                    'plan_id' => $this->put('plan_id'),
                    'stripe_subscription_id' => $plan_data['id'],
                    'stripe_subscription_data' => serialize($plan_data),
                );

                if (!$user->stripe_subscription_id) {
                    $update_data['credits'] = $user->credits + $plan->credits;
                }

                $this->User->update($user->id, $update_data);
            }
        }
    }

    public function decorate_object($object)
    {
        return decorate_user($object, true);
    }

    private function create_thumbnails($data)
    {
        /* Resize/Crop the various sizes */
        $thumbnail = $data['file_path'].'sm_'.$data['file_name'];
        $this->image_moo->load($data['full_path'])->resize_crop(IMG_SIZE_SM, IMG_SIZE_SM)->save($thumbnail, TRUE);

        $thumbnail = $data['file_path'].'md_'.$data['file_name'];
        $this->image_moo->load($data['full_path'])->resize_crop(IMG_SIZE_MD, IMG_SIZE_MD)->save($thumbnail, TRUE);

        $thumbnail = $data['file_path'].'lg_'.$data['file_name'];
        $this->image_moo->load($data['full_path'])->resize_crop(IMG_SIZE_LG, IMG_SIZE_LG)->save($thumbnail, TRUE);

        return $this->config->item('upload_url').'md_'.$data['file_name'];
    }
}

?>