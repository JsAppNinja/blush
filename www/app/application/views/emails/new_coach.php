<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p>We have tossed and turned figuring out who would be the best fit for you&hellip;and we have arrived at our decision!</p>
  <p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#DFF0D8;color: #468847; border:1px #D6E9C6 solid !important">
    <b>'.$customer->firstname.'</b>, meet <b>'.$counselor->firstname.' '.$counselor->lastname.'</b>
  </p>
  <p>If you haven\'t experienced coaching before, get excited. This is your chance to be open, honest, and free without judgment. '.$counselor->firstname.' gets it-that\'s why she\'s with Blush. You two will have the opportunity to figure out what you like, what you don\'t like, what\'s working for you, and what you wish to change. Talk without fear of anything getting around to your friends, boyfriend, or family.  '.$counselor->firstname.' is your coach now, and she\'s got your back.</p>
  <p>To get started, logon to <a style="color: #ff6494" href="'.$CI->config->item('signin_url').'">www.joinblush.com</a> to write your first journal or schedule your first video session. Journals have an unlimited word count and can be submitted day or night, and sessions last 30 minutes and need to be scheduled 24 hours in advance.</p>
  <p>Like always, if you have questions or concerns, please email us at <a style="color: #ff6494" href="mailto:'.$CI->config->item('contact_email').'">'.$CI->config->item('contact_email').'</a></p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');

/*
'<p>We have tossed and turned figuring out who would be the best fit for you&hellip;and we have arrived at our decision!</p><p style="margin-bottom:10px;padding:8px;text-shadow: 0 1px 0 #FFFFFF;border-radius:4px;background:#DFF0D8;color: #468847; border:1px #D6E9C6 solid !important"><b>{CLIENT.FIRSTNAME}</b>, meet <b>{COACH.NAME}</b></p><p>If you haven\'t experienced coaching before, get excited. This is your chance to be open, honest, and free without judgment. '.$counselor->firstname.' gets it-that\'s why she\'s with Blush. You two will have the opportunity to figure out what you like, what you don\'t like, what\'s working for you, and what you wish to change. Talk without fear of anything getting around to your friends, boyfriend, or family.  '.$counselor->firstname.' is your coach now, and she\'s got your back.</p><p>To get started, logon to <a style="color: #ff6494" href="{BLUSH.URL}">www.joinblush.com</a> and schedule your consultation at no cost! Sessions typically last between 50-60 minutes. This is a great time to discuss with {COACH.FIRSTNAME} your expectations and goals for coaching, and of course, talk behind life\'s back!</p><p>Like always, if you have questions or concerns, please email us at <a style="color: #ff6494" href="mailto:{BLUSH.EMAIL}">{BLUSH.EMAIL}</a></p><p>Blush you!</p>';
*/
?>