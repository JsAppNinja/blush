<?
include (APPPATH . '/views/emails/header.php');
$msg .= $formatted_msg;
include (APPPATH . '/views/emails/footer.php');

/**

<p>{COACH.NAME},</p><p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#DFF0D8;color: #468847; border:1px #D6E9C6 solid !important">You have been assigned a new client! Woo hoo! Log on to your account to meet <b>{CLIENT.NAME}</b></p><p>Remember to make sure your calendar is up to date so that both of you can schedule a great time for her first free session. If you are having a hard time scheduling, please let us know ASAP.</p><p>Good luck and Blush you!</p>

 */
?>