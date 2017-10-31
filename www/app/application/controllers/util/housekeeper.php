<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Housekeeper extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Event', 'Diary', 'Transaction', 'Transaction_Counselor', 'Payout', 'Plan','Registration'));
        $this->load->helper('notification');
    }

    public function index()
    {


         $this->clean_stripe_accounts();

         $this->close_out_events();
         $this->close_out_journals();

         $this->pay_coaches();

         $this->notify_upcoming_event();

         $this->notify_upcoming_unpaid_event();
       // $this->runTest();
    }

    public function runTest(){
        \Stripe\Stripe::setApiKey("sk_live_geM6GN7Fvuy2mMNGXIbl8yQP");//sk_test_tNbkzCvs0og0cdEgOJbW4NCN
//        $this->log_echo("Test Charge Attempt \n<br>");
//
//        try {
//
////            $test_charge = \Stripe\Transfer::create(array(
////                'amount' => 1000,
////                'currency' => 'usd',
////                'destination' => "acct_1BIp7OESr6g1O1Id",
////               // "source" => "ca_B6IXfzLBBuXS5z2Fpu22zFQ9ziwV831d"
////            ));
//            $test_charge = \Stripe\Charge::create(array(
//                'amount' => 1000,
//                'currency' => 'usd',
//                'customer' => "cus_BgCDoenQzfAu6U",
//                // "source" => "ca_B6IXfzLBBuXS5z2Fpu22zFQ9ziwV831d"
//            ));
//            $this->log_echo(print_r($test_charge,true));
//            $this->log_echo("Test Charge Complete\n<br>");
//
//        } catch(Exception $e){
//            $this->log_echo(print_r($e));
//        }
//
        $coaches = $this->User->get_counselors();
        foreach ($coaches as $coach) {
            try {
                $account = \Stripe\Account::create(array(
                        "from_recipient" => $coach->stripe_customer_id
                    )
                );
                $this->log_echo(print_r($account));
            }catch(Exception $e){
                $this->log_echo(print_r($e));
            }
        }
       // $this->log_echo(print_r($test_charge,true));
//        $account = \Stripe\Account::create(array(
//            "from_recipient" => "rp_103OTy2tjBa8SBT247Q5SmBJ"
//        ));
//        $this->log_echo("migrating test");
//        $this->log_echo(print_r($account,true));
    }

    public function clean_stripe_accounts()
    {
        \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));

        $this->log_echo("[Clean Stripe Accounts] Searching for stripe customers with no user...");
        $customers = \Stripe\Customer::all(array("limit" => 100));
        $index = 0;
        while (sizeof($customers->data) > 0) {
            foreach ($customers->data as $customer) {
                $this->fix_stripe_customer($customer);
                $index++;
            }
            $customers = \Stripe\Customer::all(array("limit" => 100, 'starting_after' => $customer->id));
        }
        $this->log_echo("[Clean Stripe Accounts] Finished searching ".$index." customers with no user...");

        $this->log_echo("[Clean Stripe Accounts] Searching for users with incorrect customer ids...");
        $users = $this->User->get_all_customers();
        foreach ($users as $user) {
            if ($user->stripe_customer_id) {
                try {
                    $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                } catch (Exception $e) {
                    $this->log_echo("Exception while fetching customer for user: [" . $user->firstname . " " . $user->lastname . "] " . $e->getMessage() . ". Clearing customer data.");
                    $this->User->update($user->id, array(
                        'stripe_customer_id' => NULL,
                        'stripe_registration_data' => NULL,
                        'stripe_subscription_id' => NULL,
                        'stripe_subscription_data' => NULL,
                        'credits' => 0,
                        'plan_id' => NULL
                    ));
                }

                // If the user has a customer record...
                if ($customer) {

                    // And there is a list of customers on the customer object from stripe...
                    if (isset($customer->subscriptions)) {

                        // Try to load the subscription that is set for the user, if we get an exception, that means the subscription doesn't exist
                        if ($user->stripe_subscription_id) {
                            try {
                                $subscription = $customer->subscriptions->retrieve($user->stripe_subscription_id);
                            } catch (Exception $e) {
                                $this->log_echo("Exception while fetching subscription for user: ["
                                    . $user->firstname . " " . $user->lastname . "] " . $e->getMessage());

                                // If the customer has subscriptions that we don't know about, update the record
                                if ($customer->subscriptions->count > 0) {
                                    $subscription = $customer->subscriptions->data[0];
                                    $this->log_echo("Updating user: [" . $user->firstname . " " . $user->lastname
                                        . "] with new subscription id from Stripe: " . $subscription->id);
                                    $this->User->update($user->id, array(
                                        'stripe_subscription_id' => $subscription->id,
                                        'stripe_subscription_data' => serialize($subscription)
                                    ));
                                } // Otherwise, just clear out the customer information
                                else {
                                    $this->log_echo("User: [" . $user->firstname . " " . $user->lastname . "] has incorrect subscription id: "
                                        . $user->stripe_subscription_id . " and no subscriptions. Clearing subscription id and setting credits to 0...");
                                    $this->User->update($user->id, array(
                                        'stripe_subscription_id' => NULL,
                                        'stripe_subscription_data' => NULL,
                                        'credits' => 0,
                                        'plan_id' => NULL
                                    ));

                                }
                            }
                        }
                    } // Otherwise, there are no subscription records on this customer, so set them to null
                    else if ($user->stripe_subscription_id) {
                        $this->log_echo("User: [" . $user->firstname . " " . $user->lastname . "] does not have any subscriptions, but has a subscription id: "
                            . $user->stripe_subscription_id . ". Clearing subscription id and setting credits to 0...");
                        $this->User->update($user->id, array(
                            'stripe_subscription_id' => NULL,
                            'stripe_subscription_data' => NULL,
                            'credits' => 0,
                            'plan_id' => NULL
                        ));
                    }
                }
            }
        }
        $this->log_echo("[Clean Stripe Accounts] Finished searching for users with invalid customer ids.");
    }

    /**
     * Close out all events that have taken place today and credit the amount to the counselor
     */
    public function close_out_events()
    {
        $this->log_echo("Closing Out Events...");
        $events = $this->Event->find_past_open();
        foreach ($events as $event) {
            $this->log_echo(sprintf("Closing Out Event [%d] on [%s - %s]", $event->id, $event->date, $event->start_time));
            $user = $this->User->load($event->customer_id);
            $this->log_echo(sprintf("Closing Out Event for User [%d] [%s %s] - Pre Credits [%d]", $user->id, $user->firstname, $user->lastname, $user->credits));


            $credits = max($user->credits - CREDITS_COUNSELING, 0);
            $this->log_echo(sprintf("Closing Out Event for User [%d] [%s %s] - Remaining Credits [%d]", $user->id, $user->firstname, $user->lastname, $credits));
            $this->User->update($user->id, array('credits' => $credits));
            $counselor = $this->User->load($event->counselor_id);

            /* Create a new transaction for that counselor to credit them with this diary */
            if ($counselor) {
                $this->log_echo(sprintf("Closing Out Event crediting coach: [%d] [%s %s]", $counselor->id, $counselor->firstname, $counselor->lastname));
                $transaction_id = $this->Transaction_Counselor->add(array(
                    'counselor_id' => $counselor->id,
                    'amount' => TRANSACTION_COUNSELOR_PAYOUT_VIDEO_SESSION,
                    'transaction_counselor_type_id' => TRANSACTION_COUNSELOR_TYPE_VIDEO_SESSION,
                    'object_id' => $event->id
                ));
                $this->log_echo(sprintf("Created Event Payout [%d]", $transaction_id));

                $this->Event->update($event->id, array('closed_out' => 1));
            }
        }
        $this->log_echo(sprintf("Closed Out %d Events", sizeof($events)));
    }

    public function close_out_journals()
    {
        $this->log_echo("Closing Out Journals...");
        $diaries = $this->Diary->find_responded_open();
        foreach ($diaries as $diary) {
            $user = $this->User->load($diary->user_id);
            $counselor = $this->User->load($diary->commentor_id);
            if ($counselor) {
                $this->log_echo(sprintf("Closing Out Journal for User [%d] [%s %s]", $user->id, $user->firstname, $user->lastname));
                $this->log_echo(sprintf("Closing Out Journal crediting coach: [%d] [%s %s]", $counselor->id, $counselor->firstname, $counselor->lastname));
                $transaction_id = $this->Transaction_Counselor->add(array(
                    'counselor_id' => $counselor->id,
                    'amount' => TRANSACTION_COUNSELOR_PAYOUT_DIARY,
                    'transaction_counselor_type_id' => TRANSACTION_COUNSELOR_TYPE_DIARY,
                    'object_id' => $diary->id
                ));
                $this->log_echo(sprintf("Created Journal Payout [%d]", $transaction_id));

                $this->Diary->update($diary->id, array('closed_out' => 1));
            }
        }
        $this->log_echo(sprintf("Closed Out %d Journals", sizeof($diaries)));
    }

    /**
     * Walk the list of coaches and find any transactions that are unpaid that are assigned to them.
     * Create the stripe payout and pay them.
     */
    public function pay_coaches()
    {
        \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));

        $coaches = $this->User->get_counselors();
        foreach ($coaches as $coach) {
            $this->log_echo(sprintf("Finding Payable Transactions for %s...", $coach->firstname . " " . $coach->lastname));
            $transactions = $this->Transaction_Counselor->get_unpaid_by_counselor($coach->id);
            if ($transactions) {
                if (!$coach->stripe_customer_id) {
                    notify_invalid_bank_account($coach);
                    $this->log_echo(sprintf("Notifying coach %s on invalid bank account.", $coach->firstname . " " . $coach->lastname));

                } else {
                    $recipient = null;
                    try {
                        $recipient = \Stripe\Recipient::retrieve($coach->stripe_customer_id);

                    } catch (Exception $e) {
                        $this->log_echo(sprintf("Exception while retrieving recipient for coach %s: '%s'", $coach->firstname . " " . $coach->lastname, $e->getMessage()));
                        notify_invalid_bank_account($coach);

                        $this->log_echo(sprintf("Notifying coach %s on invalid bank account.", $coach->firstname . " " . $coach->lastname));
                    }

                    if ($recipient) {
                        $amount = 0;
                        foreach ($transactions as $transaction) {
                            $this->log_echo(sprintf("Paying [%d] for Transaction ID [%d] for amount [%d]", $coach->firstname . " " . $coach->lastname, $transaction->id, $transaction->amount));
                            $amount += $transaction->amount;
                        }

                        if ($amount > 0) {
                            try {
//                                $transfer = \Stripe\Transfer::create(
//                                    array("amount" => $amount * 100,
//                                        "currency" => "usd",
//                                        "recipient" => $coach->stripe_customer_id,
//                                        "description" => "Transfer for " . $coach->firstname . " " . $coach->lastname . " for " . sizeof($transactions) . " transactions"
//                                    )
//                                );
                                $args =  array(
                                     'amount' =>$amount * 100,
                                    'currency' => 'usd',
                                    "destination" => $coach->stripe_customer_id
                                    );
                                $transfer = \Stripe\Transfer::create($args);
                                $payout_id = $this->Payout->add($transfer, $coach->id);

                                foreach ($transactions as $transaction) {
                                    $this->log_echo(sprintf("Closing Out (Marking Paid) Transaction ID [%d]", $transaction->id));
                                    $this->Transaction_Counselor->update($transaction->id, array(
                                        'paid' => 1,
                                        'date_paid' => timestamp_to_mysqldatetime(now()),
                                        'payout_id' => $payout_id
                                    ));
                                }
                            } catch (Exeption $e) {
                                $this->log_echo(sprintf("Exception '%s' while trying to create transfer for %d to coach: '%s'", $e->getMessage(), $amount, $coach->firstname . " " . $coach->lastname));
                            }
                        }
                        $this->log_echo(sprintf("Paid %d Transactions for %s totalling %d in Payout ID [%d]", sizeof($transactions), $coach->firstname . " " . $coach->lastname, $amount, $payout_id));
                    }
                }
            }
        }
    }

    /**
     * Not used or finished at this time.
     */
    public function notify_upcoming_unpaid_event()
    {
        $events = $this->Event->find_future();
        $this->log_echo("Identifying Future Unpaid Events");
        $unpaid = 0;
        foreach ($events as $event) {

        }
        $this->log_echo(sprintf("Notified %d Unpaid Events", $unpaid));
    }

    /**
     * Notify customers that they have a video session in 24 hours.
     */
    public function notify_upcoming_event()
    {
        $events = $this->Event->get_upcoming_26hours();
        array_print($events);
        foreach ($events as $event) {
            /* If the event is in the past, skip it */
            $time = strtotime($event->date . " " . $event->start_time);

            if ($time < now()) {
                continue;
            }

            notify_upcoming_video_session($event->id);
        }
        //notification_test_email();
        $this->log_echo(sprintf("Notified %d Future 24-hour Events", $events));
    }

    private function log_echo($text)
    {
        echo $text . "<br/>";
        log_message('info', $text);
    }

    private function fix_stripe_customer($customer) {
        $user = $this->User->load_by_customer_id($customer->id);
        if (!$user) {
            $this->log_echo("Stripe Customer Has No User: [" . $customer->id . "] [" . $customer->email . "]");
            $user = $this->User->load_by_email($customer->email);
            if($user) {
                $update_data = array(
                    'stripe_customer_id' => $customer->id
                );

                if ($customer->subscriptions->data) {
                    $subscription = $customer->subscriptions->data[0];
                    if ($subscription && $subscription->status === 'active') {
                        $update_data['stripe_subscription_id'] = $subscription->id;

                        $plan = $this->Plan->load_by_plan_id($subscription->plan->id);
                        if($plan) {
                            $update_data['plan_id'] = $plan->id;
                        }
                    }
                }
                $this->User->update($user->id, $update_data);
                $this->log_echo("Updating User: [" . $user->id . "] ".print_r($update_data, true));
            }
        }
    }
}