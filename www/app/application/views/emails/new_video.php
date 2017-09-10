<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#DFF0D8;color: #468847; border:1px #D6E9C6 solid !important">
    Your client <b>'.$customer->firstname.' '.$customer->lastname.'</b> has scheduled a new video session for <b>'.$start_time->format('h:i a T').' on '.pretty_date_short($event->date).'</b>
  </p>
  <p>Please double check to make sure this scheduled time works for you. If you have something else going on during that time, it means you are super popular, but it also means you need to reschedule ASAP.</p>
  <p>Put this video session date and time in your calendar now!!! We mean now!!!!!</p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>