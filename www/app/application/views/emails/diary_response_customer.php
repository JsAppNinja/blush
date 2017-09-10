<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>The wait is over! '.$counselor->firstname.' '.$counselor->lastname.' has responded to your journal entry.</p>
  <p>In order to read it, log into your account and click on your latest journal entry. Underneath you will find a response with your name on it. Happy reading!</p>';

include (APPPATH . '/views/emails/footer.php');
?>