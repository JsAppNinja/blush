<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>Hello'.$customer->firstname.'</p>
  <p>You received a message from '.$counselor->firstname.' '.$counselor->lastname.'. For privacy reasons, please log on to your Blush account to read your mail.</p>
  <p>If you have any questions please reach out to <a style="color: #ff6494" href="mailto:accounts@joinblush.com">accounts@joinblush.com</a></p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>