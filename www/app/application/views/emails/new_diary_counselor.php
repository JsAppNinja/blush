<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#DFF0D8;color: #468847; border:1px #D6E9C6 solid !important">
    Your client <b>'.$customer->firstname.' '.$customer->lastname.'</b> has submitted a Blush journal!
  </p>
  <p>Remember, you have two days to carefully read and respond to this Blush Journal entry. Your response should have considerable length and mirror the amount of effort you would put into your video session. Try to limit your grammatical errors girlfriend-we are smart humans.</p>
  <p><i>Make sure you submit your Blush Journal entry on time</i>, because you know your client is sitting on the edge of her seat waiting for your response!!</p>
  <p>If you have any questions or concerns, please let us know at <a style="color: #ff6494" href="mailto:info@joinblush.com">info@joinblush.com</a></p>';

$msg .=
  ' <p style="color:#000">If you would like to review this diary, click here:
    <a style="color:#ff6494" href="' . app_url('dashboard/customer/'.$customer->uuid).'/diaries">Review Blush Journal</a></p>';
include (APPPATH . '/views/emails/footer.php');
?>