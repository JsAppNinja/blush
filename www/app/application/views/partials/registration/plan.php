<div class="row">
    <div class="col-lg-12 title">
        <h3><strong>Choose Your Plan</strong> Pick from one of our Subscription Plans or A la Carte</h3>
    </div>
</div>

<div class="plans">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="underline">
                <h3 class="inline option">Subscription Plans</h3> <span
                    class="blush-journal">Blush Journal = 1 credit</span>
                <span class="video-session"> Video Session = 2 credits</span>
            </div>
        </div>
    </div>

    <div class="row plan-row">
        <% _ . each(pricing . plans, function (plan, iterator) { %>
            <% if (plan . stripe_plan_id == "them-makeover") { %>
                <div class="col-lg-4 col-md-4 col-sm-4 item-container plan-<%=plan.id%>">
                    <div class="plan-item-best">
                        <div class="best-value">BEST VALUE</div>
                        <div class="plan-item">
                            <h4><%= plan . name %></h4>
                            <h5 class="price"><sup>$</sup><span data-original="<%= plan . price %>"><%= plan . price %></span></h5>

                            <div><strong><%= plan . credits %></strong> credits per month</div>
                            <a class="choose" data-type="subscription" href="#" data-id="<%= plan . id %>">CHOOSE</a>
                            <a class="cancel" href="#">CANCEL</a>
                        </div>
                    </div>
                    <div class="xicon <%= plan . stripe_plan_id %>"></div>
                </div>
            <% } else if(plan.id != 7) { %>
                <div class="col-lg-4 col-md-4 col-sm-4 item-container plan-<%=plan.id%>"">
                    <div class="plan-item">
                        <h4><%= plan . name %></h4>
                        <h5 class="price"><sup>$</sup><span data-original="<%= plan . price %>"><%= plan . price %></span></h5>

                        <div><strong><%= plan . credits %></strong> credits per month</div>
                        <a class="choose" data-type="subscription" href="#" data-id="<%= plan . id %>">CHOOSE</a>
                        <a class="cancel" href="#">CANCEL</a>
                    </div>
                    <div class="xicon <%= plan . stripe_plan_id %>"></div>
                </div>
            <% } %>
        <% }) %>

        <? include(APPPATH . '/views/partials/registration/plan-card.php'); ?>
    </div>
    <div class="row">
        <p class="text-center small">Membership credits expire after a month. Your membership will automatically roll over with new credits.</p>
    </div>
</div>

<div class="alacartes">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="underline">
                <h3 class="inline option">A La Carte</h3>
            </div>
        </div>
    </div>
    <div class="row plan-row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-lg-offset-2 item-container">
            <div class="plan-item">
                <h4 class="video">Video Session</h4>
                <h5 class="price"><sup>$</sup><span data-original="<%= pricing.blush_video %>"><%= pricing.blush_video %></span></h5>
                <a class="choose" data-type="alacarte" href="#" data-id="video">CHOOSE</a>
                <a class="cancel" href="#">CANCEL</a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 item-container">
            <div class="plan-item">
                <h4 class="diary">Journal Entry</h4>
                <h5 class="price"><sup>$</sup><span data-original="<%= pricing.blush_journal %>"><%= pricing.blush_journal %></span></h5>
                <a class="choose" data-type="alacarte" href="#" data-id="journal">CHOOSE</a>
                <a class="cancel" href="#">CANCEL</a>
            </div>
        </div>

        <? include(APPPATH . '/views/partials/registration/plan-card.php'); ?>

    </div>
    <div class="row">
        <p class="text-center small">Pay As You Go credits do not expire and do roll over each month.</p>
    </div>
</div>


<div class="row">
    <input type="hidden" name="step" value="2"/>

    <div class="col-lg-12 submit-container">
        <button class="btn btn-primary previous pull-left btn-md"><i class="glyphicon glyphicon-chevron-left"></i>
            Previous
        </button>
        <div class="alert alert-success pull-right" style="display:none"></div>
        <div class="alert alert-danger pull-right" style="display:none"></div>
        <div class="clearfix"></div>
    </div>
</div>