<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>'.$customer->firstname.',</p>
  <p>I\'m sure your hands hurt from typing, but we wanted to bug you one more time to let you know that your Blush Book entry has officially been submitted!</p>
  <p>We allow '.$counselor->firstname.' '.$counselor->lastname.' 48 hours to collect her thoughts and write a thoughtful response. If you haven\'t heard from '.$counselor->firstname.' within 48 hours, please let us know so we can get mad at her.</p>
  <p>Just kidding!</p>
  <p>But we will make sure that you receive your Blush Journal entry as soon as possible! If you had any problems with your Blush Journal, or have any other questions or concerns, please let us know at <a style="color: #ff6494" href="mailto:info@joinblush.com">info@joinblush.com</a></p>
  <p>Blush you!</p>';

include (APPPATH . '/views/emails/footer.php');
?>