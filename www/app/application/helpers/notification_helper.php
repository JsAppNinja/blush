<?
require_once($_SERVER['DOCUMENT_ROOT'].'/app/vendor/autoload.php');
use Mailgun\Mailgun;

/** Loads a notification from the database and sends a test email */
function notification_test_email()
{
    $CI = & get_instance();
    $CI->load->model(array('Notification'));

    $emails = array('peter@halfslide.com');
    $mg = new Mailgun($CI->config->item('mailgun_key'));

    foreach ($emails as $email) {
        $subject = 'Test Email';
        $response = $mg->sendMessage($CI->config->item('mailgun_domain'),
            array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                'to'      => $email,
                'subject' => $subject,
                'html'    => "<p>This is a test</p>",
                'o:tracking' => 'yes',
                'o:tracking-clicks' => 'yes',
                'o:tracking-opens' => 'yes'));


        $log_text = sprintf('[Notify Password Reset] Sending test email: [%s] to %s', $subject, $email);
        log_message('info', $log_text);
        loggly(array(
            'text' => $log_text,
            'method' => 'notification_helper.notification_test_email',
            'response' => $response
        ));
    }
}


function notify_reset_password($user, $password) {
    $CI = & get_instance();

    include(APPPATH . '/views/emails/reset_password.php');

    $subject = sprintf('[%s] Password Reset', $CI->config->item('site_title'));

    $mg = new Mailgun($CI->config->item('mailgun_key'));
    $response = $mg->sendMessage($CI->config->item('mailgun_domain'),
        array('from' => $CI->config->item('notifications_email_from') . ' <' . $CI->config->item('notifications_email') . '>',
            'to' => $user->email,
            'subject' => $subject,
            'html' => $msg,
            'text' => $msg_text,
            'o:tracking' => 'yes',
            'o:tracking-clicks' => 'yes',
            'o:tracking-opens' => 'yes'));

    $log_text = sprintf('[Notify Password Reset] Sending new password: [%s] to %s', $subject, $user->email);
    log_message('info', $log_text);
    loggly(array(
        'text' => $log_text,
        'method' => 'notification_helper.notify_reset_password',
        'user' => $user,
        'response' => $response
    ));
}

/** Loads a notification from the database and sends a test email */
function notification_test($notification_id, $emails)
{
    setup_notification_email();
    $CI = & get_instance();
    $CI->load->model(array('Notification'));

    $notification = $CI->Notification->load($notification_id);
    $user = get_user();

    if ($notification) {
        $formatted_msg = process_notification_body($notification);
        include(APPPATH . '/views/emails/notification_body.php');
        foreach ($emails as $email) {
            $CI->email->subject('Test Notification: ' . $notification->name);
            $CI->email->from($CI->config->item('notifications_email'), $CI->config->item('site_title'));
            $CI->email->to($email);

            $CI->email->message($msg);
            $CI->email->send();
        }
    }
}

function process_notification_body($notification)
{
    return $notification->body;
}

/**
 * Sends a notification to the coach that the user has created a new diary
 * @param $diary_id
 */
function notify_newdiary($diary_id)
{
    if ($diary_id > 0) {
        $CI = & get_instance();
        $CI->load->model('Diary');
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $diary = $CI->Diary->load($diary_id);

        if ($diary) {
            $customer = $CI->User->load($diary->user_id);
            if ($customer) {
                $counselor = $CI->User->load_counselor($customer->id);
            }

            if ($counselor) {
                $user = $counselor;

                if ($counselor->email_diary) {
                    $subject = "Blush Journal Delivery!";
                    include(APPPATH . '/views/emails/new_diary_counselor.php');

                    $log_text = sprintf('[Notify New Diary Submission - Counselor] Sending message: [%s] to %s', $subject, $user->email);
                    $response = $mg->sendMessage($CI->config->item('mailgun_domain'),
                        array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                            'to'      => $counselor->email,
                            'subject' => $subject,
                            'html'    => $msg,
                            'o:tracking' => 'yes',
                            'o:tracking-clicks' => 'yes',
                            'o:tracking-opens' => 'yes'));
                    log_message('info', $log_text);
                    loggly(array(
                        'text' => $log_text,
                        'method' => 'notification_helper.notify_newdiary',
                        'counselor' => $counselor,
                        'diary' => $diary,
                        'customer' => $customer,
                        'response' => $response
                    ));
                }

                if ($customer->email_diary) {
                    $user = $customer;
                    $subject = "Your Blush Journal has been submitted! ";
                    include(APPPATH . '/views/emails/new_diary_customer.php');
                    $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                        'to'      => $customer->email,
                        'subject' => $subject,
                        'html'    => $msg,
                        'o:tracking' => 'yes',
                        'o:tracking-clicks' => 'yes',
                        'o:tracking-opens' => 'yes');

                    if ($customer->parent_email) {
                        $mailgun_data['cc'] = $customer->parent_email;
                    }

                    $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);
                    $log_text = sprintf('[Notify New Diary Submission - Customer] Sending message: [%s] to %s', $subject, $user->email);
                    log_message('info', $log_text);
                    loggly(array(
                        'text' => $log_text,
                        'method' => 'notification_helper.notify_newdiary',
                        'counselor' => $counselor,
                        'diary' => $diary,
                        'customer' => $customer,
                        'response' => $response
                    ));
                }
            }
        }
    }
}

/**
 * Sends a notification to the user that a coach has responded to their diary
 * @param $diary_id
 */
function notify_diaryresponse($diary_id)
{
    if ($diary_id > 0) {
        $CI = & get_instance();
        $CI->load->model('Diary');
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $diary = $CI->Diary->load($diary_id);

        if ($diary) {
            $customer = $CI->User->load($diary->user_id);
            $counselor = $CI->User->load_counselor($customer->id);
            if ($counselor) {

                if ($customer->email_diary) {
                    $user = $customer;
                    $subject = "Journal Response! ";
                    include(APPPATH . '/views/emails/diary_response_customer.php');

                    $log_text = sprintf('[Notify Diary Response Submission - Customer] Sending message: [%s] to %s', $subject, $user->email);
                    $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                        'to'      => $customer->email,
                        'subject' => $subject,
                        'html'    => $msg,
                        'o:tracking' => 'yes',
                        'o:tracking-clicks' => 'yes',
                        'o:tracking-opens' => 'yes');

                    if ($customer->parent_email) {
                        $mailgun_data['cc'] = $customer->parent_email;
                    }

                    $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

                    log_message('info', $log_text);
                    loggly(array(
                        'text' => $log_text,
                        'method' => 'notification_helper.notify_diaryresponse',
                        'response' => $response
                    ));
                }
            }
        }
    }
}

/**
 * Notify a counselor that they have a new message on the site
 * @param $counselor_id
 */
function notify_counselor_mail($counselor_id)
{
    if ($counselor_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $counselor = $CI->User->load($counselor_id);
        if ($counselor) {
            $user = $counselor;

            if ($counselor->email_message) {
                $subject = "Maaaaaail";
                include(APPPATH . '/views/emails/new_mail_counselor.php');
                $log_text = sprintf('[Notify New Counselor Mail] Sending message: [%s] to %s', $subject, $user->email);

                $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                    'to'      => $counselor->email,
                    'subject' => $subject,
                    'html'    => $msg,
                    'o:tracking' => 'yes',
                    'o:tracking-clicks' => 'yes',
                    'o:tracking-opens' => 'yes');
                $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

                log_message('info', $log_text);
                loggly(array(
                    'text' => $log_text,
                    'method' => 'notification_helper.notify_counselor_mail',
                    'response' => $response
                ));
            }
        }
    }
}

/**
 * Notify a counselor that they have a new message on the site
 * @param $counselor_id
 */
function notify_customer_mail($customer_id, $counselor_id)
{
    if ($customer_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $customer = $CI->User->load($customer_id);
        if ($customer && $customer->email_message) {
            $user = $customer;

            $counselor = $CI->User->load($counselor_id);

            $subject = "Blush Message from " . $counselor->firstname . " " . $counselor->lastname;
            include(APPPATH . '/views/emails/new_mail_customer.php');
            $log_text = sprintf('[Notify New Customer Mail] Sending message: [%s] to %s', $subject, $user->email);

            $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                'to'      => $customer->email,
                'subject' => $subject,
                'html'    => $msg,
                'o:tracking' => 'yes',
                'o:tracking-clicks' => 'yes',
                'o:tracking-opens' => 'yes');

            if ($customer->parent_email) {
                $mailgun_data['cc'] = $customer->parent_email;
            }

            $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

            log_message('info', $log_text);
            loggly(array(
                'text' => $log_text,
                'method' => 'notification_helper.notify_customer_mail',
                'response' => $response
            ));
        }
    }

}

/**
 * Notify counselor of video session scheduled
 * @param $counselor_id
 */
function notify_video_session($event_id)
{
    if ($event_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $event = $CI->Event->load($event_id);
        if ($event) {
            $counselor = $CI->User->load($event->counselor_id);
            $customer = $CI->User->load($event->customer_id);

            date_default_timezone_set(getenv('TZ'));
            $start_time = new DateTime($event->date . " " . $event->start_time);
            log_message('info', '[Notify New Video Session] Event Date: '.$start_time->format('h:i a e'));
            if ($counselor->timezone) {
                try {
                    $start_time->setTimezone(new DateTimeZone($counselor->timezone));
                    log_message('info', "[Notify New Video Session] Event Date Converted to Coach Timezone: ".$start_time->format('h:i a e'));
                } catch(Exception $e) {
                    log_message('error', 'Unable to adjust timezone for user: '.$counselor->timezone.'. Exception: '.$e->getMessage());
                }
            }

            if ($counselor) {
                $user = $counselor;

                $subject = "New video session!";
                include(APPPATH . '/views/emails/new_video.php');
                $log_text = sprintf('[Notify New Video Session] Sending message: [%s] to %s', $subject, $user->email);

                $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                    'to'      => $counselor->email,
                    'subject' => $subject,
                    'html'    => $msg,
                    'o:tracking' => 'yes',
                    'o:tracking-clicks' => 'yes',
                    'o:tracking-opens' => 'yes');
                $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

                log_message('info', $log_text);
                loggly(array(
                    'text' => $log_text,
                    'method' => 'notification_helper.notify_video_session',
                    'response' => $response
                ));
            }
        }
    }
}

/**
 * Notify counselor of video session scheduled
 * @param $counselor_id
 */
function notify_upcoming_video_session($event_id)
{
    if ($event_id > 0) {

        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));
        $plivo = new Plivo\RestAPI($CI->config->item('plivo_auth_id'), $CI->config->item('plivo_auth_token'));

        $event = $CI->Event->load($event_id);

        if ($event) {
            $counselor = $CI->User->load($event->counselor_id);
            $customer = $CI->User->load($event->customer_id);

            date_default_timezone_set(getenv('TZ'));
            $start_time = new DateTime($event->date . " " . $event->start_time);
            log_message('info', '[Notify Upcoming Video Session] Event Date: '.$start_time->format('h:i a e'));
            if ($customer->timezone) {
                try {
                    $start_time->setTimezone(new DateTimeZone($customer->timezone));
                    log_message('info', "[Notify Upcoming Video Session] Event Date Converted to Customer Timezone: ".$start_time->format('h:i a e'));
                } catch(Exception $e) {
                    log_message('error', 'Unable to adjust timezone for user: '.$customer->timezone.'. Exception: '.$e->getMessage());
                }
            }

            // Send the notification email to the customer
            if ($customer) {
                $user = $customer;

                $subject = "Reminder!";
                include(APPPATH . '/views/emails/upcoming_video_session.php');
                if($customer->email_reminder) {
                    $log_text = sprintf('[Notify Upcoming Video Session] Sending message: [%s] to %s', $subject, $customer->email);

                    $mailgun_data = array('from' => $CI->config->item('notifications_email_from') . ' <' . $CI->config->item('notifications_email') . '>',
                        'to' => $customer->email,
                        'subject' => $subject,
                        'html' => $msg,
                        'o:tracking' => 'yes',
                        'o:tracking-clicks' => 'yes',
                        'o:tracking-opens' => 'yes');

                    if ($customer->parent_email) {
                        $mailgun_data['cc'] = $customer->parent_email;
                    }

                    $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

                    log_message('info', $log_text);
                    loggly(array(
                        'text' => $log_text,
                        'method' => 'notification_helper.notify_upcoming_video_session',
                        'response' => $response
                    ));
                }

                // Send the notification sms to the customer
                if($customer->sms_reminder && $customer->mobile_phone) {
                    $text = 'Blush Video Session Reminder: You have a session tomorrow at '.$start_time->format('h:i a T').' with '
                        .$counselor->firstname.' '.$counselor->lastname;
                    $data = array(
                        'dst' => '1'.clean_phone($customer->mobile_phone),
                        'src' => $CI->config->item('plivo_number'),
                        'text' => $text
                    );
                    $response = $plivo->send_message($data);
                    $log_text = sprintf('[Notify Upcoming Video Session] Sending sms message session reminder: [%s] to %s', $text, $customer->mobile_phone);
                    log_message('info', $log_text);

                    if(isset($response['response']['error'])) {
                        log_message('error', 'Failed to send plivo sms message: '.$response['response']['error']);
                    }

                    loggly(array(
                        'text' => $log_text,
                        'method' => 'notification_helper.notify_upcoming_video_session',
                        'response' => $response
                    ));
                }
            }

            // Send the notification email to the counselor
            if($counselor) {
                $user = $counselor;

                date_default_timezone_set(getenv('TZ'));
                $start_time = new DateTime($event->date . " " . $event->start_time);
                if ($counselor->timezone) {
                    try {
                        $start_time->setTimezone(new DateTimeZone($counselor->timezone));
                        log_message('info', "[Notify Upcoming Video Session] Event Date Converted to Coach Timezone: ".$start_time->format('h:i a e'));
                    } catch(Exception $e) {
                        log_message('error', 'Unable to adjust timezone for user: '.$counselor->timezone.'. Exception: '.$e->getMessage());
                    }
                }

                if($counselor->email_reminder) {
                    log_message('info', '[Notify Upcoming Video Session] Event Date: ' . $start_time->format('h:i a e'));
                    $subject = "Reminder!";
                    include(APPPATH . '/views/emails/upcoming_video_session_coach.php');
                    $log_text = sprintf('[Notify Upcoming Video Session] Sending message: [%s] to %s', $subject, $counselor->email);

                    $mailgun_data = array('from' => $CI->config->item('notifications_email_from') . ' <' . $CI->config->item('notifications_email') . '>',
                        'to' => $counselor->email,
                        'subject' => $subject,
                        'html' => $msg,
                        'o:tracking' => 'yes',
                        'o:tracking-clicks' => 'yes',
                        'o:tracking-opens' => 'yes');

                    $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

                    log_message('info', $log_text);
                    loggly(array(
                        'text' => $log_text,
                        'method' => 'notification_helper.notify_upcoming_video_session',
                        'response' => $response
                    ));
                }

                // Send the notification sms to the customer
                if($counselor->sms_reminder && $counselor->mobile_phone) {
                    $text = 'Blush Video Session Reminder: You have a session tomorrow at '.$start_time->format('h:i a T').' with '
                        .$customer->firstname.' '.$customer->lastname;
                    $data = array(
                        'dst' => '1'.clean_phone($counselor->mobile_phone),
                        'src' => $CI->config->item('plivo_number'),
                        'text' => $text
                    );
                    $response = $plivo->send_message($data);
                    $log_text = sprintf('[Notify Upcoming Video Session] Sending sms message session reminder: [%s] to %s', $text, $counselor->mobile_phone);
                    log_message('info', $log_text);

                    if(isset($response['response']['error'])) {
                        log_message('error', 'Failed to send plivo sms message: '.$response['response']['error']);
                    }

                    loggly(array(
                        'text' => $log_text,
                        'method' => 'notification_helper.notify_upcoming_video_session',
                        'response' => $response
                    ));
                }
            }
        }
    }
}


/**
 * Notify counselor of video session cancelled
 * @param $counselor_id
 */
function notify_cancel_video_counselor($event_id)
{
    if ($event_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $event = $CI->Event->load($event_id);
        if ($event) {
            $counselor = $CI->User->load($event->counselor_id);
            $customer = $CI->User->load($event->customer_id);

            date_default_timezone_set(getenv('TZ'));
            $start_time = new DateTime($event->date . " " . $event->start_time);
            if ($counselor->timezone) {
                try {
                    $start_time->setTimezone(new DateTimeZone($counselor->timezone));
                } catch(Exception $e) {
                    log_message('error', 'Unable to adjust timezone for user: '.$counselor->timezone.'. Exception: '.$e->getMessage());
                }
            }

            if ($counselor) {
                $user = $counselor;

                $subject = "Video Session Cancelled!";
                include(APPPATH . '/views/emails/cancelled_video_counselor.php');
                $log_text = sprintf('[Notify Cancelled Video Session] Sending message: [%s] to %s', $subject, $user->email);

                $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                    'to'      => $counselor->email,
                    'subject' => $subject,
                    'html'    => $msg,
                    'o:tracking' => 'yes',
                    'o:tracking-clicks' => 'yes',
                    'o:tracking-opens' => 'yes');
                $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

                log_message('info', $log_text);
                loggly(array(
                    'text' => $log_text,
                    'method' => 'notification_helper.notify_cancel_video_counselor',
                    'response' => $response
                ));
            }
        }
    }
}


/**
 * Notify counselor of video session cancelled
 * @param $counselor_id
 */
function notify_cancel_video_customer($event_id)
{
    if ($event_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $event = $CI->Event->load($event_id);
        if ($event) {
            $counselor = $CI->User->load($event->counselor_id);
            $customer = $CI->User->load($event->customer_id);

            date_default_timezone_set(getenv('TZ'));
            $start_time = new DateTime($event->date . " " . $event->start_time);
            if ($customer->timezone) {
                try {
                    $start_time->setTimezone(new DateTimeZone($customer->timezone));
                } catch(Exception $e) {
                    log_message('error', 'Unable to adjust timezone for user: '.$customer->timezone.'. Exception: '.$e->getMessage());
                }
            }

            if ($customer) {
                $user = $customer;

                $subject = "Video Session Cancelled!";
                include(APPPATH . '/views/emails/cancelled_video_customer.php');
                $log_text = sprintf('[Notify Cancelled Video Session] Sending message: [%s] to %s', $subject, $user->email);

                $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                    'to'      => $customer->email,
                    'subject' => $subject,
                    'html'    => $msg,
                    'o:tracking' => 'yes',
                    'o:tracking-clicks' => 'yes',
                    'o:tracking-opens' => 'yes');

                if ($customer->parent_email) {
                    $mailgun_data['cc'] = $customer->parent_email;
                }

                $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

                log_message('info', $log_text);
                loggly(array(
                    'text' => $log_text,
                    'method' => 'notification_helper.notify_cancel_video_customer',
                    'response' => $response
                ));
            }
        }
    }
}

/**
 * Send an email to the new user who has just joined blush
 * @param $customer_id
 */
function notify_new_user($customer_id)
{
    if ($customer_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $customer = $CI->User->load($customer_id);

        $user = $customer;
        $subject = "Thank you for joining Blush!";
        include(APPPATH . '/views/emails/welcome.php');
        $log_text = sprintf('[Notify New Customer] Sending message: [%s] to %s', $subject, $user->email);

        $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
            'to'      => $customer->email,
            'bcc' =>  $CI->config->item('notifications_user'),
            'subject' => $subject,
            'html'    => $msg,
            'o:tracking' => 'yes',
            'o:tracking-clicks' => 'yes',
            'o:tracking-opens' => 'yes');

        if ($customer->parent_email) {
            $mailgun_data['cc'] = $customer->parent_email;
        }

        $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

        log_message('info', $log_text);
        loggly(array(
            'text' => $log_text,
            'method' => 'notification_helper.notify_new_user',
            'response' => $response
        ));
    }
}

/**
 * Send an email to the new user's parents for confirmation
 * @param $customer_id
 */
function notify_parent_approval($customer_id, $registration_key)
{
    if ($customer_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $customer = $CI->User->load($customer_id);

        $user = $customer;
        $subject = "Blush Parental Approval!";
        include(APPPATH . '/views/emails/parent_approval.php');
        $log_text = sprintf('[Notify Parent Approval] Sending message: [%s] to %s', $subject, $user->email);

        $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
            'to'      => $customer->parent_email,
            'subject' => $subject,
            'html'    => $msg,
            'o:tracking' => 'yes',
            'o:tracking-clicks' => 'yes',
            'o:tracking-opens' => 'yes');
        $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

        log_message('info', $log_text);
        loggly(array(
            'text' => $log_text,
            'method' => 'notification_helper.notify_parent_approval',
            'response' => $response
        ));
    }
}

/**
 * Send an email to the coach that they just got paid.
 * @param $counselor_id
 * @param $transfer
 */
function notify_payment($counselor_id, $transfer)
{
    if ($counselor_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $counselor = $CI->User->load($counselor_id);

        if ($counselor->email_purchase) {
            $user = $counselor;
            $amount = dollarfy($transfer['amount'] / 100);
            $subject = "You Got PAID!";
            include(APPPATH . '/views/emails/payment.php');

            $log_text = sprintf('[Notify Payment] Sending message: [%s] to %s', $subject, $user->email);

            $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                'to'      => $counselor->email,
                'subject' => $subject,
                'html'    => $msg,
                'o:tracking' => 'yes',
                'o:tracking-clicks' => 'yes',
                'o:tracking-opens' => 'yes');
            $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

            log_message('info', $log_text);
            loggly(array(
                'text' => $log_text,
                'method' => 'notification_helper.notify_payment',
                'response' => $response
            ));
        }
    }
}

/**
 * Notify a counselor that they do not have a valid back account
 */
function notify_invalid_bank_account($counselor)
{
    if ($counselor && $counselor->id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));
        $user = $counselor;

        $subject = "Invalid Bank Account";
        include(APPPATH . '/views/emails/invalid_bank_account.php');
        $log_text = sprintf('[Notify Invalid Bank Account] Sending message: [%s] to %s', $subject, $counselor->email);

        $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
            'to'      => $counselor->email,
            'subject' => $subject,
            'html'    => $msg,
            'o:tracking' => 'yes',
            'o:tracking-clicks' => 'yes',
            'o:tracking-opens' => 'yes');
        $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

        log_message('info', $log_text);
        loggly(array(
            'text' => $log_text,
            'method' => 'notification_helper.notify_invalid_bank_account',
            'response' => $response
        ));
    }
}

function notify_error($subject, $error) {
    $CI = & get_instance();
    $mg = new Mailgun($CI->config->item('mailgun_key'));
    $email = 'peter@halfslide.com';
    $log_text = sprintf('[Notify Error] Sending message: [%s] to %s', $subject, $email);

    $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
        'to'      => $email,
        'subject' => $subject,
        'html'    => '<p>'.$error.'</p>',
        'o:tracking' => 'yes',
        'o:tracking-clicks' => 'yes',
        'o:tracking-opens' => 'yes');
    $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

    log_message('info', $log_text);
    loggly(array(
        'text' => $log_text,
        'method' => 'notification_helper.notify_error',
        'response' => $response
    ));

}

/**
 * Notify counselor of new client
 * @param $counselor_id
 */
function notify_new_customer($counselor_id, $customer_id)
{
    if ($counselor_id > 0) {
        $CI = & get_instance();
        $mg = new Mailgun($CI->config->item('mailgun_key'));

        $counselor = $CI->User->load($counselor_id);
        if ($counselor) {
            $user = $counselor;

            $customer = $CI->User->load($customer_id);

            $subject = "NEW CLIENT!";
            include(APPPATH . '/views/emails/new_client.php');
            $log_text = sprintf('[Notify New Client] Sending message: [%s] to %s', $subject, $user->email);

            $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                'to'      => $counselor->email,
                'subject' => $subject,
                'html'    => $msg,
                'o:tracking' => 'yes',
                'o:tracking-clicks' => 'yes',
                'o:tracking-opens' => 'yes');
            $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

            log_message('info', $log_text);
            loggly(array(
                'text' => $log_text,
                'method' => 'notification_helper.notify_new_customer',
                'response' => $response
            ));


            $user = $customer;
            $subject = "It's a match!";
            include(APPPATH . '/views/emails/new_coach.php');
            $log_text = sprintf('[Notify New Coach] Sending message: [%s] to %s', $subject, $user->email);

            $mailgun_data = array('from' => $CI->config->item('notifications_email_from').' <'.$CI->config->item('notifications_email').'>',
                'to'      => $customer->email,
                'subject' => $subject,
                'html'    => $msg,
                'o:tracking' => 'yes',
                'o:tracking-clicks' => 'yes',
                'o:tracking-opens' => 'yes');

            if ($customer->parent_email) {
                $mailgun_data['cc'] = $customer->parent_email;
            }

            $response = $mg->sendMessage($CI->config->item('mailgun_domain'), $mailgun_data);

            log_message('info', $log_text);
            loggly(array(
                'text' => $log_text,
                'method' => 'notification_helper.notify_new_customer',
                'response' => $response
            ));
        }
    }
}

function setup_notification_email()
{
    $CI = & get_instance();

    $config = array();
    $config['protocol'] = 'smtp';
    $config['mailtype'] = 'html';
    $config['smtp_host'] = $CI->config->item('smtp_host');
    $config['smtp_port'] = $CI->config->item('smtp_port');
    $config['smtp_user'] = $CI->config->item('notifications_user');
    $config['smtp_pass'] = $CI->config->item('notifications_password');
    $config['smtp_timeout'] = 5;
    $config['charset'] = 'iso-8859-1';
    $config['wordwrap'] = TRUE;

    $CI->load->library('email', $config);
    $CI->email->set_newline("\r\n");
}

?>