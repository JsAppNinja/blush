<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>Just got paid, Friday night!</p>
  <p>Yes, your hard work has paid off! You have received '.$amount.' for your incredible Blush wisdom. Keep up the good work.</p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>