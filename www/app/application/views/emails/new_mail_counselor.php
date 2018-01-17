<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>Hey '.$counselor->firstname.',</p>
  <p>You received a message from '.$customer->firstname.' '.$customer->lastname. '. Login to your Blush Dashboard to read your new message. </p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>