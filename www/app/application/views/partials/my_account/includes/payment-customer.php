<%if(user_type_id == app.user_type_customer) {
    var valid_customer = stripe_customer && stripe_customer.id;
    if(valid_customer && stripe_card){%>
<div class="existing-card">
    <p>The following credit-card is on-file as the card that we will use to pay for purchases. If you would like to change it, please use the form below.</p>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label>Type</label>
                <p><%= stripe_card.type %></p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label>Expiration Date</label>
                <p><%= stripe_card.exp_month %>/<%= stripe_card.exp_year %></p>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label>Last 4 digits of Card</label>
                <p><%= stripe_card.last4 %></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a href="#" class="btn btn-sm btn-pink card-fields-toggle">Change Card</a>
        </div>
    </div>
</div>
<% } %>
<div class="card-fields"  <% if (valid_customer && stripe_card) {%>style="display:none"<% } %>>
    <div class="row">
        <div class="col-lg-9">
            <div class="form-group">
                <label class="sr-only" for="card-number">Credit Card Number</label>
                <input type="text" class="form-control stripe-sensitive card-number" id="card-number"
                       placeholder="Credit Card Number" tabindex="1" <% if (!valid_customer) {%>data-rule-required="true"<% } %>>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label class="sr-only" for="card-number">CVC</label>
                <input type="text" class="form-control stripe-sensitive card-cvc" id="card-cvc" placeholder="CVC"
                       autocomplete="off" maxlength="4" <% if (!valid_customer) {%>data-rule-required="true"<% } %> tabindex="2"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="card-expiry-month">Month</label>
                <?= form_month('', '', FALSE, 'class="form-control card-expiry-month stripe-sensitive"') ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="card-expiry-year">Year</label>
                <?= form_year_future('', NULL, 'class="form-control card-expiry-year stripe-sensitive"') ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="cardholder-name" value="<%=firstname+" "+lastname%>"/>
<% } %>