<% if(user_type_id == app.user_type_counselor) { %>
<div id="dashboard" class="counselor">
    <div class="upcoming-event" style="display:none">
        <div class="col-lg-9 col-md-9 col-sm-9 text-right">
            <p class="text"></p>
        </div>
        <div class="col-lg-3 col-md-3 col-md-3">
            <button class="start-session btn">
                <i class="glyphicons play_button"></i> Start Session
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-8">
            <div class="alert-container" style="display:none"></div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-md-3 hidden-xs">
                    <img src="<%= picture %>" class="img-circle img-thumbnail"/>
                </div>

                <div class="col-lg-9 col-md-9 col-sm-9 ">
                    <h5>About Me</h5>
                    <p><%= about_pretty %></p>
                    <% if(!about_pretty) { %>
                        <a href="<?=site_url('my_account')?>" class="btn btn-sm btn-pink">Edit This</a>
                    <% } %>
                </div>
            </div>

            <div class="row user-meta">
                <div class="col-lg-4 col-md-6 col-sm-6 text-center name col">
                    <span class="name"><%= firstname+" "+lastname %></span>
                </div>
                <div class="col-lg-4  col-md-6 col-sm-6 col-lg-offset-4 text-right location col">
                    <% if(city && state) { %><span>
                        <i class="glyphicon glyphicon-map-marker"></i>
                        <%= city+", "+state %></span>
                    <% } %>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-4" id="sidebar">
            <div class="section no-bottom no-top attached-top">
                <ul class="list-unstyled nav">
                    <li><a href="#" class="calendar"><i class="glyphicons calendar"></i> Calendar</a></li>
                    <li><a href="#" class="messages"><i class="glyphicons comments"></i> Messages
                            <% if(new_message_count > 0) { %><span class="badge badge-pink"><%=new_message_count%></span><% } %>
                        </a></li>
                    <li><a href="#" class="customers"><i class="glyphicons group"></i> Customers</a></li>
                </ul>
            </div>

            <div class="white-arrow hidden-md hidden-sm"></div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row" id="dashboard-container"></div>
        </div>
    </div>
</div>
<% } %>