<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#F2DEDE;color: #A94442; border:1px #EBCCD1 solid !important">
    Your client <b>'.$customer->firstname.' '.$customer->lastname.'</b> has cancelled her video session for <b>'.$start_time->format('h:i a T').' on '.pretty_date_short($event->date).'</b>
  </p>
  <p>If you would like to reschedule your session with your client, please contact them through the site as soon as possible.</p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>