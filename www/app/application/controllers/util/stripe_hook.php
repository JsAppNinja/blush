<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stripe_Hook extends MY_Controller
{

    var $emails;
    var $id_range;
    var $attachment_dir;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $postdata = file_get_contents("php://input");
        if($postdata) {
            $event = json_decode($postdata);
            if ($event) {
                log_message('info', sprintf('[Stripe Event] Processing [%s] Hook', $event->type));

                switch ($event->type) {
                    case "invoice.payment_succeeded":
                        $this->payment_succeeded($event);
                        break;
                }

                //log_message('info', sprintf('[Stripe Event] Event [%s] Details: [%s] ', $event->type, print_r($event, TRUE)));
            }
        }  else {
            echo "NO INPUT";
        }
    }

    private function payment_succeeded($event)
    {
        $customer_id = $event->data->object->customer;
        $user = $this->User->load_by_customer_id($customer_id);

        if ($user) {
            log_message('info', sprintf('[Stripe Event] Processing [%s] for User: %s', $event->type, $user->username));

            /* Look at the lines and see if this is for a subscription */
            if(isset($event->data->object->lines)) {
                $this->load->model(array('Plan','Event'));
                foreach($event->data->object->lines->data as $invoice_item) {
                    $stripe_plan = $invoice_item->plan;
                    $plan = $this->Plan->load_by_plan_id($stripe_plan->id);

                    /* If the user has any events scheduled coming up, don't delete the credits for those */
                    $credits_to_expunge = $plan->credits;
                    $events = $this->Event->find_future_customer($user->id);
                    $credits_to_expunge = $credits_to_expunge - (sizeof($events) * CREDITS_COUNSELING);
                    $credits_to_expunge = max(0, $credits_to_expunge);
                    log_message('info', sprintf('[Stripe Event] [%s]: %d Credits Expunged FROM User: %s for Plan: %s', $event->type, $credits_to_expunge,  $user->username, $plan->name));


                    /* Update the user and add more credits to their account */
                    //$credits = max(0, $user->credits - $credits_to_expunge) + $plan->credits;
                    /* Just zero out their credits and add the plan credits */
                    $credits = $plan->credits;

                    $this->User->update($user->id, array('credits'=> $credits));
                    log_message('info', sprintf('[Stripe Event] [%s]: %d Credits Set for User: %s for Plan: %s', $event->type, $plan->credits,  $user->username, $plan->name));
                }
            }
        } else {
            $this->load->helper('notification');
            log_message('error', sprintf('[Stripe Event] Processing [%s] failed for Customer Id: %s',$event->type, $customer_id));
            notify_error(sprintf('[Blush] Stripe Event Processing [%s] failed for Customer Id: %s',$event->type, $customer_id), $event);
        }
    }

}

?>