<h4>Account Type</h4>

<% if (stripe_customer_id) { %>


    <p>If you would like to change your account subscription plan, choose from any of the following below.
        If you change your account, your changes will not go into effect until your next billing cycle.
        At that time, your account will be billed a prorated amount and the appropriate credits will be added to your
        account.</p>
    <form action="" method="post" id="profile-form" class="std-form">
        <div id="plans">
            <div class="row plan-row">
                <% _ . each(pricing . plans, function (pricing_plan, iterator) { %>
                    <% if (pricing_plan . stripe_plan_id == "them-makeover") { %>
                        <div
                            class="col-lg-4 col-md-4 col-sm-4 item-container plan-<%= pricing_plan . id %> <% if (plan_id == pricing_plan . id) { %>current<% } %>">
                            <div class="plan-item-best">
                                <div class="best-value">BEST VALUE</div>
                                <div class="plan-item">
                                    <h4><%= plan . name %></h4>
                                    <h5 class="price"><sup>$</sup><span
                                            data-original="<%= pricing_plan . price %>"><%= pricing_plan . price %></span>
                                    </h5>

                                    <div><strong><%= pricing_plan . credits %></strong> credits per month</div>
                                    <a class="choose" data-type="subscription" href="#"
                                       data-id="<%= pricing_plan . id %>">CHOOSE</a>
                                    <span class="chosen" href="#">Current Plan</span>
                                </div>
                            </div>
                        </div>
                    <% } else if(pricing_plan.id != 7)  { %>
                        <div
                            class="col-lg-4 col-md-4 col-sm-4 item-container plan-<%= pricing_plan . id %> <% if (plan_id == pricing_plan . id) { %>current<% } %>">
                            <div class="plan-item">
                                <h4><%= pricing_plan . name %></h4>
                                <h5 class="price"><sup>$</sup><span
                                        data-original="<%= pricing_plan . price %>"><%= pricing_plan . price %></span>
                                </h5>

                                <div><strong><%= pricing_plan . credits %></strong> credits per month</div>
                                <a class="choose" data-type="subscription" href="#" data-id="<%= pricing_plan . id %>">CHOOSE</a>
                                <span class="chosen" href="#">Current Plan</span>
                            </div>
                        </div>
                    <% } %>
                <% }) %>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <% if(plan_id > 0) { %>
                        <p>If you would like to cancel your plan, please contact
                            <a href="mailto:shanece@joinblush.com">shanece@joinblush.com</a>
                        </p>
                    <% } %>
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
<% } else { %>
    <div class="alert alert-danger">
        <p>In order to sign up for a subscription plan, you must first enter your payment information.
            <a href="<?= site_detect_url('my_account/payment') ?>">Click here</a> to enter
            your payment information.</p>
    </div>
<% } %>