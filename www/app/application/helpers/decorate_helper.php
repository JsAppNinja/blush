<?
function decorate_user($object, $isDeep = false)
{
    $CI =& get_instance();
    $CI->load->model(array('Message', 'Diary', 'Event', 'Plan'));

    if(!$object->id) {
        return $object;
    }

    if($isDeep) {
        $object->counselor = get_counselor($object->id);
        /* List New messages */
        $object->new_message_count = intval($CI->Message->get_count_new($object->id));

        if ($object->user_type_id == USER_TYPE_COUNSELOR) {
            $object->new_diary_count = intval($CI->Diary->get_count_new($object->id));
        } else {
            $object->new_diary_count = intval($CI->Diary->get_count_new_comments($object->id));

            /* Verify that the user has enough credits with upcoming events to schedule the event */
            $pending_events = $CI->Event->find_future_customer($object->id);
            $object->pending_credits = (sizeof($pending_events) * CREDITS_COUNSELING);
        }

        $object->stripe_card = null;
        $object->stripe_customer = null;
        $stripe_customer = get_stripe_customer($object->id);
        $object->billing_end = null;
        if (isset($stripe_customer) && $stripe_customer) {
            //array_print($stripe_customer);
            $cards = $stripe_customer->sources;
            if($cards && $cards->data) {
                $card = $cards->data[0];
                $object->stripe_card = new stdClass;
                $object->stripe_card->id = $card->id;
                $object->stripe_card->type = $card->brand;
                $object->stripe_card->brand = $card->brand;
                $object->stripe_card->exp_month = $card->exp_month;
                $object->stripe_card->exp_year = $card->exp_year;
                $object->stripe_card->last4 = $card->last4;
                if($stripe_customer->subscriptions->data) {
                    $object->billing_end = date('F d, Y', $stripe_customer->subscriptions->data[0]->current_period_end);
                }
            }
            $object->stripe_customer = new stdClass;
            $object->stripe_customer->id = $stripe_customer->id;
        }
        $object->plan = new stdClass;
        if ($object->plan_id) {
            $object->plan_id = intval($object->plan_id);
            $object->plan = $CI->Plan->load($object->plan_id);
        }
    }
    $object->about_pretty = nl2br($object->about);
    $object->avatar = get_avatar(IMG_SIZE_LG, $object);
    $object->birthday = pretty_date_short($object->birthday);
    $object->created = pretty_date_time_short($object->created);
    $object->last_login = pretty_date_time_short($object->last_login);
    $object->picture = $object->avatar;
    $object->mobile_phone = phone_format($object->mobile_phone);
    $object->credits = intval($object->credits);
    $object->email_diary = intval($object->email_diary);
    $object->email_general = intval($object->email_general);
    $object->email_message = intval($object->email_message);
    $object->email_purchase = intval($object->email_purchase);
    $object->email_reminder = intval($object->email_reminder);


    $object->user_type_id = intval($object->user_type_id);
    $object->welcomed = intval($object->welcomed);

    unset($object->id);
    unset($object->password);
    unset($object->salt);
    unset($object->stripe_registration_data);
    unset($object->deleted);
    unset($object->inactive);

    //array_print($object);

    return $object;
}

function decorate_event($object) {
    $CI =& get_instance();
    $object->day = date("j", strtotime($object->date));
    $object->month = date("M", strtotime($object->date));

    $start_time = new DateTime($object->date." ".$object->start_time);
    $end_time = new DateTime($object->date." ".$object->end_time);

    $user = get_user();

    $object->unpaid=0;
    /** If user doesn't have the credits for this event, mark it unpaid */
    if($user->user_type_id==USER_TYPE_CUSTOMER) {
        if($user->credits < CREDITS_COUNSELING) {
            $object->unpaid=1;
        }
    }

    if($user->timezone) {
        $start_time->setTimezone(new DateTimeZone($user->timezone));
        $end_time->setTimezone(new DateTimeZone($user->timezone));
    }
    $timespan = $start_time->format('g:i a')." - ".$end_time->format('g:i a');

    /* Build title */
    if($user->id==$object->customer_id) {
        $counselor = $CI->User->load($object->counselor_id);
        $title = "<strong>".$timespan."</strong> Video Session with <strong>".$counselor->firstname." ".$counselor->lastname."</strong>";
        $object->title = $title;
    } else {
        $customer = $CI->User->load($object->customer_id);
        $title = "<strong>".$timespan."</strong> Video Session with <strong>".$customer->firstname." ".$customer->lastname."</strong>";
        $object->title = $title;
    }

    unset($object->session_id);
    unset($object->customer_token);
    unset($object->counselor_token);

    $object->text = nl2br($object->text);
    return $object;
}

function decorate_availability_calendars($availability_calendars, $user) {
    $updated = array();
    foreach($availability_calendars as $calendar) {
        $updated[] = decorate_availability_calendar($calendar, $user);
    }
    return $updated;
}

function decorate_availability_calendar($object, $user) {
    if(intval($object->is_available)<0) {
        $object->cls = 'unavailable';
        $object->title = 'Unvailable';
    } else {
        $object->cls = 'available';
        $object->title = 'Available';
    }
    if($object->is_all_day) {
        $object->title.=' All Day';
    } else {

        $user = get_user();
        $start_time = new DateTime($object->date." ".$object->start_time);
        $end_time = new DateTime($object->date." ".$object->end_time);

        if($user->timezone) {
            $start_time->setTimezone(new DateTimeZone($user->timezone));
            $end_time->setTimezone(new DateTimeZone($user->timezone));
        }
        $timespan = $start_time->format('g:i a')." to ".$end_time->format('g:i a');
        $object->title.=' from '.$timespan;
    }
    return $object;
}



function decorate_availabilities($availabilities, $user) {
    $updated = array();
    foreach($availabilities as $availability) {
        $updated[] = decorate_availability($availability, $user);
    }
    return $updated;
}

/*
 * Convert the date/time from the server's default of CST to the user's timezone
 */
function decorate_availability($obj, $user)
{
    date_default_timezone_set(getenv('TZ'));
    $startdatetime = new DateTime($obj->start_time);
    if ($user->timezone) {
        $startdatetime->setTimezone(new DateTimeZone($user->timezone));
    }
    $obj->start_time = $startdatetime->format('H:i:s');

    $enddatetime = new DateTime($obj->end_time);
    if ($user->timezone) {
        $enddatetime->setTimezone(new DateTimeZone($user->timezone));
    }
    $obj->end_time = $enddatetime->format('H:i:s');

    $obj->day_of_week = number_to_day($obj->day);
    $obj->pretty_start_time = pretty_time($obj->start_time);
    $obj->pretty_end_time = pretty_time($obj->end_time);


    return $obj;
}
?>