<form action="" method="post" class="std-form">
    <div class="card-fields col-lg-8" style="display:none">
        <div class="coupon-code-container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>First Month Coupon Code</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="sr-only" for="coupon-code">First Month Coupon Code</label>

                        <div class="input-group">
                            <input type="text" class="form-control coupon-code"
                                   placeholder="Coupon Code">
                            <span class="input-group-btn">
                                <button class="btn btn-pink coupon-code-button" type="button">Apply</button>
                                <button class="btn btn-danger coupon-code-reset-button" type="button" style="display:none">Remove</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 coupon-code-alerts">
                    <div class="alert alert-success" style="display:none">
                        <small>The coupon code you have entered has been successfully applied.  You will receive the price on the plan to the left for the first month and then be charged the regular price for each month after.</small>
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <small>The coupon code you have entered is invalid.</small>
                    </div>
                </div>
            </div>
        </div>
        <p><strong>Make sure you hit the 'apply' button to apply the coupon code to your purchase.</strong></p>
        <div class="row">
            <div class="col-lg-12">
                <h3>Credit Card Information</h3>

                <p>Please enter your credit card information below to complete your purchase.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="sr-only" for="card-number">Name on the Card</label>
                    <input type="text" class="form-control stripe-sensitive cardholder-name"
                           placeholder="Name on the Card" data-rule-required="true">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9">
                <div class="form-group">
                    <label class="sr-only" for="card-number">Credit Card Number</label>
                    <input type="text" class="form-control stripe-sensitive card-number"
                           placeholder="Credit Card Number" data-rule-required="true">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label class="sr-only" for="card-number">CVC</label>
                    <input type="text" class="form-control stripe-sensitive card-cvc"
                           placeholder="CVC" data-rule-required="true"
                           autocomplete="off" maxlength="4"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="sr-only" for="card-expiry-month">Month</label>
                    <?= form_month('', '', FALSE, 'class="form-control card-expiry-month stripe-sensitive" data-rule-required="true"') ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="sr-only" for="card-expiry-year">Year</label>
                    <?= form_year_future('', NULL, 'class="form-control card-expiry-year stripe-sensitive" data-rule-required="true"') ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 card-submit-container">
                <button class="btn btn btn-primary submit pull-right btn-md" data-loading-text="Saving...">Submit
                </button>
                <button class="btn cancel pull-right btn-md">Cancel</button>
                <div class="alert alert-success pull-right" style="display:none"></div>
                <div class="alert alert-danger pull-right" style="display:none"></div>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</form>