<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#DFF0D8;color: #468847; border:1px #D6E9C6 solid !important">
    This is a friendly reminder that you have a session with <b>'.$customer->firstname.' '.$customer->lastname.'</b> <b>TOMORROW at '.$start_time->format('h:i a T').'</b>. Please add this time to your calendar ASAP!.
  </p>
  <p>Remember, if your client is not on time for her session; send her a message to make sure everything is ok!</p>
  <p>If you need anything or have any questions, feel free to email <a href="mailto:info@joinblush.com">info@joinblush.com</a>.</p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>