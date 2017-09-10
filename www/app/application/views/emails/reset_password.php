<?php
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>You are receiving this email because you recently requested that your '.$CI->config->item('site_title')
   . ' password be emailed to you.  <strong>Your new password is: ' . $password . '</strong></p>
   <p>Thank you.</p>';

$msg_text = 'You are receiving this email because you recently requested that your '.$CI->config->item('site_title')
    . ' password be emailed to you.  Your new password is: ' . $password . '
   Thank you.';

include (APPPATH . '/views/emails/footer.php');
?>