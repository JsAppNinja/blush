<?
include (APPPATH . '/views/emails/header.php');
$msg .=
  '<p><strong>Welcome! You\'re a Blush Girl now.</strong></p>
  <p>First things first -- <strong>make sure you fill out your profile located under "My Account"</strong> so we can match you with your ideal coach. If you don\'t fill our your profile, we have nothing to work with, and cannot match you. So chop, chop missy!</p>
  <p>Once we have made the match, we will alert you via email. Don\'t worry, this shouldn\'t take more than a few hours...that is unless you\'re filling out this information at midnight. If that\'s the case, go to bed, and we\'ll get to it as soon as we have coffee in our system!</p>
  <p>After you have filled out your profile and are matched with your coach, you can begin to use the Blush website! Here are some tips.</p>
  <h3><em>For Video Sessions...</em></h3>
  <ol>
    <li>Always use a <strong>Chrome browser</strong> on your desktop. Always. And, if you have an Android tablet or phone, you can use Blush anywhere! Yay!</li>
    <li>Sessions are thirty minutes long. For a full hour, simply schedule back to back.</li>
    <li>Sessions need to be scheduled, rescheduled, or canceled within <strong>24 hours</strong>.</li>
    <li>All session times and appointments reflect your selected timezone. If traveling/moving, you can change your timezone under your Account Information.</li>
  </ol>
  <h3><em>For Journals...</em></h3>
  <ol>
    <li>Click <em>"+Blush Journal"</em> in your Dashboard to create a new entry.</li>
    <li>Write as much as you would like. There is no limit!</li>
    <li>Submit day or night.</li>
    <li>Your coach will respond within 48 hours. You will receive an email notification when she has responded!</li>
  </ol>
  <p>If you run out of credits at any time, you can add more by going to your Dashboard and clicking "add credits." (Remember, you need 1 for a journal entry and 2 for a video session).</p>
  <p>If you haven\'t joined a membership yet, you can do so in your Account Information under the <em>"Account Type"</em> tab.</p>
  <p>Blush you!</p>';
include (APPPATH . '/views/emails/footer.php');
?>