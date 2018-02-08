<form action="" method="post" id="payment-form" class="std-form">
    <h4>Payment Settings</h4>

    <?
    include(APPPATH.'views/partials/my_account/includes/payment-customer.php');
    include(APPPATH.'views/partials/my_account/includes/payment-counselor.php');
    ?>
    <h4>By saving your data here you are agreeing to the Stripe Terms of Service as outlined below</h4>
    <div class="row">
        <div class="col-lg-12">
            Payment processing services for Coaches on blush are provided by Stripe and are subject to the
            <a href="https://stripe.com/connect-account/legal">Stripe Connected Account Agreement</a>, which includes the <a href="https://stripe.com/legal">Stripe Terms of Service</a> (collectively, the “Stripe Services Agreement”). By agreeing to this agreement or continuing to operate as a Coach on blush, you agree to be bound by the Stripe Services Agreement, as the same may be modified by Stripe from time to time. As a condition of blush enabling payment processing services through Stripe, you agree to provide blush accurate and complete information about you and your business, and you authorize blush to share it and transaction information related to your use of the payment processing services provided by Stripe.</p>
        </div>
    </div>
    <h4>Location Settings</h4>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="country">Country</label>
                <?= form_countries('country', '', 'class="form-control" tabindex="7"') ?>
            </div>
            <div class="form-group">
                <label class="sr-only" for="address">Address</label>
                <input type="text" class="form-control" id="address" placeholder="Address" name="address" tabindex="9"
                       value="<%= address %>">
            </div>
            <div class="form-group">
                <label class="sr-only" for="email">State</label>
                <?= form_states('state', '', TRUE, 'class="form-control" data-rule-required="true" tabindex="11"') ?>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" tabindex="8"
                       value="<%= phone %>">
            </div>
            <div class="form-group">
                <label class="sr-only" for="city">City</label>
                <input type="text" class="form-control" id="city" placeholder="City" data-rule-required="true"
                       name="city" tabindex="10" value="<%= city %>">
            </div>
            <div class="form-group">
                <label class="sr-only" for="zipcode">Zip</label>
                <input type="text" class="form-control" id="zipcode" placeholder="Zip" name="zipcode" tabindex="12"
                       value="<%= zipcode %>">
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