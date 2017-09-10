<form action="" method="post">
    <div class="title">
        <div class="row">
            <div class="icon col-lg-1 col-md-2 col-xs-2">
                <i class="glyphicons circle_info"></i>
            </div>
            <div class="col-lg-11  col-md-10 col-xs-10">
                <h2><%= firstname + " " + lastname %></h2>
            </div>
        </div>
    </div>

    <div class="body">
        <div class="row">
            <div class="col-lg-6">
                <address>
                    <%= address %><br/>
                    <%= city %>, <%= state %> <%= zipcode %><br/>
                    <a href="mailto:<%= email %>"><%= email %></a><br/>
                    Ph: <%= phone %>
                </address>
            </div>
            <div class="col-lg-6">
                <label>Birthday</label>: <%= birthday %><br/>
                <label>Date Joined</label>: <%= created %><br/>
                <label>Last Login</label>: <%= last_login %><br/>
                <label>Timezone</label>: <%= timezone %><br/>
                <label>Plan</label>: <%= plan.name %><br/>
            </div>
        </div>

        <? include(APPPATH.'views/partials/admin/user/registration-data.php'); ?>
    </div>

</form>