<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>Hi '.$customer->firstname.',</p>
  <p>Your Blush Journal has been submitted!</p>
  <p>We allow '.$counselor->firstname.' '.$counselor->lastname.' 48 hours to collect her thoughts and write a thoughtful response. If you have any questions about your journal submission, please reach out to <a style="color: #ff6494" href="mailto:info@joinblush.com">info@joinblush.com</a>.</p>
  <p>Blush you!</p>';

include (APPPATH . '/views/emails/footer.php');
?>