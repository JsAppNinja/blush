<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#DFF0D8;color: #468847; border:1px #D6E9C6 solid !important">
    You have a session tomorrow at <b>'.$start_time->format('h:i a T').'</b> with <b>'.$counselor->firstname.' '.$counselor->lastname.'</b>.
  </p>
  <p>Just a reminder for video sessions...</p>
  <ol>
    <li>Make sure you are using the Chrome browser</li>
    <li>Please use a laptop or Google powered tablets/phones</li>
    <li>Log on and click "start session" under your Dashboard</li>
    <li>If it\'s your first session, allow Blush access to your mic/camera</li>
    <li>Smile! Your session is starting!</li>
  </ol>
  <p>If you need to cancel or reschedule, you have two hours to do so. Literally. Otherwise, see you tomorrow!</p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>