<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#F2DEDE;color: #A94442; border:1px #EBCCD1 solid !important">
    You do not have a valid back account on file with Blush.
  </p>
  <p>We have attempted to pay you and can not find a valid bank account for you in our system.  In order to receive payouts from the work you are doing, you must log into your account and add your bank account.  To do this, go to your payments settings under My Account and enter your bank account.</p>
  <p>Good luck and Blush you!</p>';

include (APPPATH . '/views/emails/footer.php');

/**

<p>{COACH.NAME},</p><p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#DFF0D8;color: #468847; border:1px #D6E9C6 solid !important">You have been assigned a new client! Woo hoo! Log on to your account to meet <b>{CLIENT.NAME}</b></p><p>Remember to make sure your calendar is up to date so that both of you can schedule a great time for her first free session. If you are having a hard time scheduling, please let us know ASAP.</p><p>Good luck and Blush you!</p>

 */
?>