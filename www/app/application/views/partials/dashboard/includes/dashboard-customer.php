<% if(user_type_id == app.user_type_customer) { %>
<div id="dashboard">
    <div class="upcoming-event" style="display:none">
        <div class="col-lg-9  col-md-9 col-sm-8 text-right">
            <p class="text"></p>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4">
            <button class="start-session btn">
                <i class="glyphicons play_button"></i> Start Session
            </button>
        </div>
    </div>

    <div class="btn-group pull-right" id="dashboard-controls">
        <button type="button" class='btn btn-lg' id="btn-add-diary"><i class="fa fa-plus"></i> Blush Journal</button>
        <button type="button" class='btn btn-lg' id="btn-add-video"><i class="fa fa-plus"></i> Video Session</button>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-8">
            <div class="alert-container" style="display:none"></div>
            <div class="row">
                <div class="col-lg-3 hidden-xs">
                    <img src="<%= picture %>" class="img-circle img-thumbnail"/>
                </div>

                <div class="col-lg-9">
                    <h5>About Me</h5>
                    <p><%= about_pretty %></p>
                    <% if(!about_pretty) { %>
                        <a href="<?=site_url('my_account')?>" class="btn btn-sm btn-pink">Edit This</a>
                    <% } %>
                </div>
            </div>

            <div class="row user-meta">
                <div class="col-lg-4 col-md-4 col-sm-4 text-center name col">
                    <span class="name"><%= firstname+" "+lastname %></span>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 text-center messages col">
                    <a href="#">Messages
                        <% if(new_message_count > 0) { %><span class="badge badge-pink"><%=new_message_count%></span><% } %>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-4 text-center diaries col">
                    <a href="#">Journals
                        <% if(new_diary_count > 0) { %><span class="badge badge-pink"><%=new_diary_count%></span><% } %>
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 hidden-sm text-right location col">
                    <% if(city && state) { %><span>
                        <i class="glyphicon glyphicon-map-marker"></i>
                        <%= city+", "+state %></span>
                    <% } %>
                </div>
            </div>

            <div class="row" id="dashboard-alerts"></div>

            <div class="row" id="dashboard-container"></div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-4" id="sidebar">
            <div class="section">
                <h4 class="top">Available Credits</h4>

                <div class="credits icon-statistic">
                    <i></i> <strong><%=credits-pending_credits%></strong> Credits
                </div>
                <% if(billing_end) { %>
                Your next billing cycle begins on <strong><%=billing_end%></strong>
                <% } %>

                <h4>Services</h4>

                <div class="diary icon-statistic">
                    <i></i> <strong>Blush Journal</strong> = <?=CREDITS_DIARY?> Credits
                </div>
                <div class="session icon-statistic">
                    <i></i> <strong>Session</strong> = <?=CREDITS_COUNSELING?> Credits
                </div>
            </div>

            <div class="brown-arrow">
                <button class="btn btn-primary btn-lg customer" id="btn-add-credits">Add Credits</button>
            </div>

            <div class="top-arrow hidden-xs"></div>

            <div class="section no-top">
                <% if(counselor.uuid) { %>
                    <h4 class="top plain">Counselor</h4>

                    <div class="counselor">
                        <p>
                            <img src="<%= counselor.avatar %>" class="img-circle img-thumbnail pull-left"/>
                            <span><%=counselor.firstname+" "+counselor.lastname %></span>
                        </p>
                        <div class="clearfix"></div>

                        <p class="about"><%= counselor.about_pretty%></p>
                    </div>
                <% } %>
                <h4>Your Schedule</h4>

                <div id="sidebar-calendar"></div>
            </div>
        </div>

    </div>
</div>
<% } %>