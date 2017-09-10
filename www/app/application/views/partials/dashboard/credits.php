<div id="add-credits">
    <div class="header">
        <div class="row">
            <div class="col-lg-7">
                <h4>Make a payment</h4>

                <h3 class="total"></h3>
            </div>
            <div class="col-lg-5">
                <img src="/assets/images/logo.png" class="responsive"/>
            </div>
        </div>

        <div class="row" id="add-credits-form">
            <% if (!stripe_customer_id) { %>
                <div class="alert alert-danger text-center"><strong>No Payment Method</strong></div>

                <p>There is currently no payment method on record with our system. To purchase credits, you must go to
                    the payment
                    settings page and enter your payment method. To add your payment method,
                    <a href="<?= site_detect_url('my_account/payment') ?>"><strong>click here</strong></a>.</p>
            <% } else { %>
                <p class="alert alert-danger no-selection-error" style="display:none">Please add at least one diary or
                    counseling session before clicking submit.</p>
                <form action="" method="post" class="credit-form">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="input-group diary-count">
                                <span class="input-group-addon plus"><i class="glyphicons plus"></i></span>
                                <input type="text" class="form-control" value="0">
                                <span class="input-group-addon minus"><i class="glyphicons minus"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <p>Blush Journal Submissions</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="input-group counseling-count">
                                <span class="input-group-addon plus"><i class="glyphicons plus"></i></span>
                                <input type="text" class="form-control" value="0">
                                <span class="input-group-addon minus"><i class="glyphicons minus"></i></span>
                            </div>

                        </div>
                        <div class="col-lg-7">
                            <p>Counseling Sessions</p>
                        </div>
                    </div>
                </form>
            <% } %>
        </div>
        <div class="row" id="add-credits-confirm" style="display:none">
            <h3>Confirm Purchase</h3>

            <p class="alert alert-danger" style="display:none"></p>

            <p>To complete your purchase of your diary/counseling sessions, click the "Complete Purchase" button below.
                The credit card you have on file will be charged and the credits will be added to your account.</p>

            <p class="text-center">
                <button class="btn btn-lg btn-success submit" data-loading-text="Submitting Payment...">Submit Payment
                </button>
            </p>
        </div>
    </div>
</div>