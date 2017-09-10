<?
class Events extends REST_Controller {

    /* TODO: Need to secure these calls */

    function __construct() {
        parent::__construct();
        $this->load->model('Event');
        $this->load->helper('json');
    }

    /**
     * Returns a list of events for the current user
     */
    public function index_get() {
        $start = $this->get('start');
        /* Get the list of events between start/end */
        $user = get_user();
        if($start) {
            $end = $this->get('end');

            if($user->user_type_id === USER_TYPE_CUSTOMER) {
                $events = $this->Event->get_list(999, 0, NULL, NULL, $user->id, 0, $start, $end);
            } else {
                $events = $this->Event->get_list(999, 0, NULL, NULL, 0, $user->id, $start, $end);
            }
        }
        /* Get the list of next 5 upcoming events for the user */
        else {
            $events = $this->Event->get_list(5, 0, NULL, NULL, $user->id, 0, now());

        }
        $this->response($this->decorate_objects($events));
    }

    public function event_get($uuid = '') {

        $user = get_user();
        $event = $this->Event->load_by_uuid($uuid);

        if($event->customer_id == $user->id || $event->counselor_id == $user->id) {
            $this->response($this->decorate_object($event));
        } else {
            http_response_code(403);
            exit;
        }
    }

    public function cancel_post() {

        $uuid = $this->post('uuid');
        $user = get_user();
        $event = $this->Event->load_by_uuid($uuid);
        $this->load->helper('date');

        $date = strtotime($event->date." ".$event->start_time);
        $date24 = add_day(1);

        if($date < $date24) {
            json_error('You cannot cancel an event within 24 hours of its start.');
            return;
        }


        $this->load->helper('notification');
        if($event->customer_id == $user->id) {
            notify_cancel_video_counselor($event->id);
            $this->Event->update($event->id, array('deleted'=>1));
            json_success('Event has been cancelled successfully.  Your coach has been notified that your session has been cancelled.',
                array('user' =>decorate_user(get_user(), true)));

        } else if($event->counselor_id == $user->id) {
            notify_cancel_video_customer($event->id);
            $this->Event->update($event->id, array('deleted'=>1));
            json_success('Event has been cancelled successfully.  Your client has been notified that your session has been cancelled.',
                array('user' =>decorate_user(get_user(), true)));

        } else {
            http_response_code(403);
            exit;
        }
    }

    /**
     * When the user selects a date for a video session, get any custom timeslots for the user's coach
     */
    public function timeslots_post() {
        $this->load->model(array('Availability', 'Availability_Calendar'));
        $this->load->helper('util');
        $customer = get_user();
        $counselor = $this->User->load_counselor($customer->id);
        $date = mysql_date($this->post('session_date'));
        if($customer) {
            $availabilities = $this->Availability->get_by_user($counselor->id);
            $availability_calendars = $this->Availability_Calendar->get_by_user($counselor->id, $date);

            $timeslots = calculate_counselor_timeslots($availabilities, $availability_calendars, $this->post('session_date'), $customer, $counselor);

            $this->response($timeslots);
        } else {
            json_error('Unable to determine timeslots');
        }
    }

    /**
     * Occurs when the customer schedules a new video session with their counselor
     */
    public function video_add_post() {
        $this->load->model(array('Availability', 'Availability_Calendar'));
        $customer = get_user();

        $counselor = $this->User->load_counselor($customer->id);
        $start_time = $this->post('session_time');
        $date = $this->post('session_date');

        /* Verify that the time is not in the past */
        if($customer->timezone) {
            date_default_timezone_set($customer->timezone);
        }
        $datetime = new DateTime(mysql_date($date)." ".$start_time);
        $datetime->setTimezone(new DateTimeZone(getenv('TZ')));

        if($datetime->getTimestamp() < now()) {
            json_error('The date you have entered is in the past.  Please schedule your video session for a time in the future.');
            exit;
        }

        /* Verify that the user has enough credits with upcoming events to schedule the event */
        $pending_events = $this->Event->find_future_customer($customer->id);
        $pending_credits = (sizeof($pending_events) * CREDITS_COUNSELING);
        $remaining_credits = $customer->credits - CREDITS_COUNSELING - $pending_credits;
        if($remaining_credits < 0) {
            log_message('error', sprintf('Customer %s cannot schedule a new coaching session due to pending sessions.  '
                .'Credits: [%d], Pending Credits: [%d], Remaining Credits: [%d]', $customer->email, $customer->credits, $pending_credits, $remaining_credits));
            json_error('You do not have enough credits to schedule a new session.',
                array('remaining_credits' => $remaining_credits, 'pending_credits' => $pending_credits, 'credits' => $customer->credits));
        }

        /* Verify that the time is in the counselor's calendar availability slots
            -returns -1 if the coach has a set availability that has them unavailable
            -returns 0 if they do not have an availability calendar item set
            -returns 1 if they have an availability item that has them available (and should override the timeslots)
        */
        $availability_calendar = $this->Availability_Calendar->validate($counselor->id, $datetime);
        if($availability_calendar<0) {
            json_error('The date you have entered is during a time that your Coach will be unavailable.  Please choose a different time.');
            exit;
        }

        /* If they don't have an availability set, fall back to the timeslots (if availability_calendar < 1*/
        if($availability_calendar===0) {
            /* Verify that the time is in the counselor's slots */
            $availability = $this->Availability->validate($counselor->id, $datetime);
            if(!$availability) {
                json_error('The date you have entered is not in your Coach\'s list of open time slots.  Please choose a different time.');
                exit;
            }
        }

        $event = $this->Event->blank();

        if($customer && $counselor) {
            /* Only set fields for finding an existing event */
            $data = array(
                'counselor_id' => $counselor->id,
                'date' => mysql_date($this->post('session_date')),
                'start_time'=> $datetime->format('H:i')
            );

            $existing = $this->Event->find_existing($data);

            if(!$existing) {

                $datetime->modify('+30 minutes');

                $data['customer_id'] = $customer->id;
                $data['title'] = 'Video Session';
                $data['end_time'] = $datetime->format('H:i');

                $eventId = $this->Event->add($data);

                $this->load->helper('notification');
                notify_video_session($eventId);

                $event = $this->Event->load($eventId);
            } else {
                json_error('Your counselor already has an event scheduled for that window.  Please choose a different time.');
                exit;
            }
        }
        json_success('Your event has been scheduled successfully.', array('event'=>$event, 'user' =>decorate_user(get_user(), true)));
    }

    /**
     * Returns an event only if the event is happening in the next 60 minutes
     */
    public function upcoming_get() {
        $user = get_user();
        $event = $this->Event->blank();

        if($user) {
            if($user->user_type_id==USER_TYPE_CUSTOMER) {
                $event = $this->Event->get_upcoming($user->id, 0);
            } else {
                $event = $this->Event->get_upcoming(0, $user->id);
            }
            if($event) {
                $event = $this->decorate_object($event);
                $event->minutes_remaining = round((strtotime($event->date." ".$event->start_time) - now())/60);
            }
            $this->response($event);
        }
    }

    public function decorate_object($object) {
        return decorate_event($object);
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $updated_objects[] = $this->decorate_object($object);
        }
        return $updated_objects;
    }
}
?>