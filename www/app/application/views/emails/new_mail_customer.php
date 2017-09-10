<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>This is the best news EVER!</p>
  <p>You have mail in your Blush account. For privacy reasons, please log on to your Blush account in order to read your super cool message.</p>
  <p>Have a fantastic day.</p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>