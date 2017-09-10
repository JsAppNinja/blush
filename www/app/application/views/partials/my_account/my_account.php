<div id="my_account">
    <div class="row">

        <div class="col-lg-3" id="sidebar">
            <div class="section no-bottom no-top attached-top">
                <ul class="list-unstyled nav side-nav">
                    <li><a href="#" class="profile"><i class="glyphicons cogwheel"></i> Profile Settings</a></li>
                    <li><a href="#" class="notifications"><i class="glyphicons envelope"></i> Notifications</a></li>
                    <li><a href="#" class="payment"><i class="glyphicons credit_card"></i> Payment Settings</a></li>
                    <li><a href="#" class="password"><i class="glyphicons lock"></i> Change Password</a></li>
                    <% if (app.user.user_type_id == app.user_type_customer) { %>
                        <li><a href="#" class="account_type"><i class="glyphicons girl"></i> Account Type</a></li>
                    <% } else { %>
                        <li><a href="#" class="availability"><i class="glyphicons calendar"></i> Availability</a></li>
                    <% } %>
                </ul>
            </div>

            <div class="white-arrow"></div>
        </div>

        <div class="col-lg-9">
            <div class="row" id="my-account-container"></div>
        </div>

    </div>
</div>
