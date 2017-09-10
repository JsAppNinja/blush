<?php
include(APPPATH.'views/partials/my_account/includes/sms-customer.php');
?>

<h4>Email Settings</h4>

<p>Select the actions that you would like to be notified of by choosing below:</p>
<form action="" method="post" id="profile-form" class="std-form">

<?
include(APPPATH.'views/partials/my_account/includes/email-customer.php');
include(APPPATH.'views/partials/my_account/includes/email-counselor.php');
?>

<div class="row">
    <div class="col-lg-12 submit-container">
        <button class="submit btn btn-primary pull-right btn-lg" data-loading-text="Saving...">Save</button>
        <div class="alert alert-success pull-right" style="display:none"></div>
        <div class="alert alert-danger pull-right" style="display:none"></div>
    </div>
</div>
</form>