<h4>Change Password</h4>

<p>If you would like to change your password, enter your existing and new passwords below:</p>
<form action="" method="post" id="profile-form" class="std-form">

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="password">Existing Password</label>
                <input type="password" class="form-control" id="existing_password" placeholder="Existing Password" name="existing_password" tabindex="1" data-rule-required="true" data-rule-minlength="6">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="password">New Password</label>
                <input type="password" class="form-control" id="new_password" placeholder="New Password" name="new_password" tabindex="2" data-rule-required="true" data-rule-minlength="6">
            </div>
            <div class="form-group">
                <label class="sr-only" for="confirm_password">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" placeholder="Confirm New Password" name="confirm_password" tabindex="2" data-rule-equalTo="#new_password" data-rule-required="true">
            </div>
         </div>
    </div>

    <div class="row">
        <div class="col-lg-12 submit-container">
            <button class="submit btn btn-primary pull-right btn-lg" data-loading-text="Saving...">Save</button>
            <div class="alert alert-success pull-right" style="display:none"></div>
            <div class="alert alert-danger pull-right" style="display:none"></div>
        </div>
    </div>
</form>