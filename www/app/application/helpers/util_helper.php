<?
function template_include($path, $id)
{
    echo "<script type='text/html' id='" . $id . "'>";
    include($path);
    echo "</script>";
}

function calculate_counselor_timeslots($availabilities, $availability_calendars, $date, $customer, $counselor)
{

    $day_of_week = date("l", strtotime($date));
    $response = new stdClass;
    $timeslots = array();
    $blocks = array();
    foreach ($availabilities as $availability) {
        if (number_to_day($availability->day) === $day_of_week) {
            $timeslots[] = $availability;
        }
    }
    //array_print($timeslots);

    // Set the timezone to the customer's timezone so we can set the start/end dates correctly
    $start = new DateTime(mysql_date($date), new DateTimeZone($customer->timezone));
    $end = new DateTime(mysql_date($date), new DateTimeZone($customer->timezone));
    $end->add(new DateInterval('P1D'));

    //echo 'Start: ' . $start->format('F j, Y H:i:s A T') . "<br/>";
    //echo 'End: ' . $end->format('F j, Y H:i:s A T') . "<br/><br/>";

    /**
     * Build up the timeslots that the coach is available starting with 00:00:00 and all the way to 23:30:00 every
     * thirty minutes
     */
    while ($start < $end) {
        foreach ($timeslots as $timeslot) {
            $block = $start->format("H:i:s");
            $timeslot_start = new DateTime(mysql_date($date) . ' ' . $timeslot->start_time, new DateTimeZone(getenv('TZ')));
            $timeslot_end = new DateTime(mysql_date($date) . ' ' . $timeslot->end_time, new DateTimeZone(getenv('TZ')));

            // Translate it to the customer's timezone
            if ($customer->timezone) {
                $timeslot_start->setTimezone(new DateTimeZone($customer->timezone));
                $timeslot_end->setTimezone(new DateTimeZone($customer->timezone));
            }

            // Add a day to the end date if it is less than the start
            if($timeslot_start > $timeslot_end) {
                $timeslot_end->add(new DateInterval('P1D'));
            }

            //echo 'Block Start: ' . $start->format('F j, Y H:i:s A T') . "<br/>";
            //echo $timeslot->id." ".$timeslot->start_time." ".$timeslot->end_time."<br/>";
            //echo 'Timeslot Start: ' . $timeslot_start->format('F j, Y H:i:s A T') . "<br/>";
            //echo 'Timeslot End: ' . $timeslot_end->format('F j, Y H:i:s A T') . "<br/><br/>";
            if ($start >= $timeslot_start && ($start < $timeslot_end)) {
                $blocks[] = $block;
            }
        }

        $start->add(new DateInterval('PT30M'));
    }

    //array_print($blocks);

    /**
     * Walk the user's calendars and remove any timeslots that they are not available
     */
    foreach ($availability_calendars as $calendar) {
        //array_print($calendar);
        if ($calendar->is_available < 0) {

            // If they are unavailable all day, just return an empty array
            if ($calendar->is_all_day > 0) {
                return array();
            }

            // When we load the dates from the database, they will be in CST
            $unavailable_start = new DateTime($calendar->start_time, new DateTimeZone(getenv('TZ')));
            $unavailable_end = new DateTime($calendar->end_time, new DateTimeZone(getenv('TZ')));

            // Translate the dates to the customer's timezone
            $unavailable_start->setTimezone(new DateTimeZone($customer->timezone));
            $unavailable_end->setTimezone(new DateTimeZone($customer->timezone));

            // Add a day to the end date if it is less than the start
            if($unavailable_start > $unavailable_end) {
                $unavailable_end->add(new DateInterval('P1D'));
            }

            //echo 'Unavailable Start: ' . date('F j, Y H:i:s A T', $unavailable_start->getTimestamp()) . "<br/>";
            //echo 'Unavailable End: ' . date('F j, Y H:i:s A T', $unavailable_end->getTimestamp()) . "<br/><br/>";
            while ($unavailable_start < $unavailable_end) {
                $block = $unavailable_start->format("H:i:s");
                //echo 'Block: '.$block."<br/>";
                $index = array_search($block, $blocks);
                //echo $index." ".$block."<br/>";
                if ($index !== FALSE && $index >= 0) {
                    unset($blocks[$index]);
                }
                $unavailable_start->add(new DateInterval('PT30M'));
            }
        } else {
            $available_start = new DateTime($calendar->start_time, new DateTimeZone(getenv('TZ')));
            $available_end = new DateTime($calendar->end_time, new DateTimeZone(getenv('TZ')));
            $available_start->setTimezone(new DateTimeZone($customer->timezone));
            $available_end->setTimezone(new DateTimeZone($customer->timezone));

            if($available_start > $available_end) {
                $available_end->add(new DateInterval('P1D'));
            }
            while ($available_start < $available_end) {
                $block = $available_start->format("H:i:s");
                //echo 'Block: '.$block."<br/>";

                $index = in_array($block, $blocks);
                if (!$index) {
                    $blocks[] = $block;
                }
                $available_start->add(new DateInterval('PT30M'));
            }
        }
    }

    sort($blocks);
    $timeslots = array();
    $timeslot = new stdClass;
    foreach ($blocks as $index => $block) {
        if ($index < 1) {
            $timeslot->start_time = new DateTime(mysql_date($date) . " " . $block);
            $timeslot->end_time = new DateTime(mysql_date($date) . " " . $block);
        } else {
            $timeslot->end_time->add(new DateInterval('PT30M'));
            if (date("H:i:s", $timeslot->end_time->getTimeStamp()) === $block) {

            } else {
                if ($customer->timezone) {
                    $timeslot->start_time->setTimezone(new DateTimeZone($customer->timezone));
                    $timeslot->end_time->setTimezone(new DateTimeZone($customer->timezone));
                }
                // Decorate it:
                $timeslot->timespan = $timeslot->start_time->format('g:i a') . " to " . $timeslot->end_time->format('g:i a');

                $timeslots[] = $timeslot;

                $timeslot = new stdClass;
                $timeslot->start_time = new DateTime(mysql_date($date) . " " . $block);
                $timeslot->end_time = new DateTime(mysql_date($date) . " " . $block);
            }
        }
    }
    if (isset($timeslot->start_time)) {
        // Decorate it:
        // Add 30 minutes to the end time
        $timeslot->end_time->add(new DateInterval('PT30M'));
        $timeslot->timespan = $timeslot->start_time->format('g:i a') . " to " . $timeslot->end_time->format('g:i a');
        $timeslots[] = $timeslot;
    }

    $response->blocks = $blocks;
    $response->timeslots = $timeslots;
    //array_print($response);


    date_default_timezone_set(getenv('TZ'));
    return $response;
}

?>