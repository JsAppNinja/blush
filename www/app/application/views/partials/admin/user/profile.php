<div class="tab-pane active" id="profile">
    <form action="" method="post" id="profile-form" class="std-form">
        <h4>Profile Settings</h4>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="sr-only" for="firstname">First Name</label>
                    <input type="text" class="form-control" id="firstname" placeholder="First Name" name="firstname" value="<%= firstname %>" data-rule-required="true">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="email">Email</label>
                    <input type="text" class="form-control" id="email" placeholder="Email" name="email" value="<%= email %>" data-rule-required="true"
                           data-rule-remote="<?=app_url('accounts/query_email')?>?uuid=<%=uuid%>">
                </div>
                <div class="form-group date">
                    <label class="sr-only" for="birthday">Birthday</label>
                    <input type="text" class="form-control datepicker" id="birthday" placeholder="Birthday" name="birthday" value="<%= birthday %>">
                    <i class="glyphicons calendar"></i>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="sr-only" for="lastname">Last Name</label>
                    <input type="text" class="form-control" id="lastname" placeholder="Last Name" name="lastname" value="<%= lastname %>" data-rule-required="true">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="mobile_phone">Mobile Phone</label>
                    <input type="text" class="form-control" id="mobile_phone" placeholder="Mobile Phone" name="mobile_phone" value="<%= mobile_phone %>">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="gender">Gender</label>
                    <select name="gender" class="form-control" id="gender" placeholder="Gender">
                        <option value="">Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
        </div>

        <h4>Subscription Information</h4>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <% if(plan.name) { %>
                        <label>Plan: <%=plan.name%></label>
                    <% } else { %>
                        <label>Plan: <em>No Plan Selected</em></label>
                    <% } %>
                </div>
            </div>
            <div class="col-lg-6">

                <div class="form-group">
                    <label for="credits">Credits</label>
                    <input type="number" class="form-control" id="credits" placeholder="Credits" name="credits" value="<%= credits %>">
                </div>
            </div>
        </div>

        <h4>Login Information</h4>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="sr-only" for="username">Username</label>
                    <input type="text" class="form-control" id="username" placeholder="Username" name="username"
                           value="<%= username %>" data-rule-required="true" data-rule-remote="<?=app_url('accounts/query_username')?>?uuid=<%=uuid%>">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="sr-only" for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" <% if (!uuid) { %> data-rule-required="true" data-rule-minlength="6" <% } %> value="">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" name="confirm_password"  <% if (!uuid) { %> data-rule-equalTo="#password" data-rule-required="true" <% } %> value="">
                </div>
            </div>
        </div>

        <h4>Location Settings</h4>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="sr-only" for="country">Country</label>
                    <?= form_countries('country', '', 'class="form-control"') ?>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="address">Address</label>
                    <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="<%= address %>">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="email">State</label>
                    <?= form_states('state', '', TRUE, 'class="form-control"') ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="sr-only" for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" placeholder="Phone" name="phone" value="<%= phone %>">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="phone">City</label>
                    <input type="text" class="form-control" id="city" placeholder="City" name="city" value="<%= city %>">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="zipcode">Zip</label>
                    <input type="text" class="form-control" id="zipcode" placeholder="Zip" name="zipcode" value="<%= zipcode %>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 submit-container">
                <a href="<?=site_url('admin/login/user/')?>/<%=uuid%>" class="btn btn-default pull-left">Login As</a>
                <button class="submit btn btn-primary pull-right btn-md" data-loading-text="Saving...">Save</button>
                <button class="cancel btn pull-right btn-md">Cancel</button>
                <div class="alert alert-success pull-right" style="display:none"></div>
                <div class="alert alert-danger pull-right" style="display:none"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 ">
                <a href="#delete_modal" data-toggle="modal" class="pull-left delete icon-link"><i class="glyphicons bin"></i> Permanently Delete Account</a>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Sure you wanna do that?</h2>
            </div>
            <div class="modal-body">
                <p>Deleting this account is permanent & cannot be undone.</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-delete-confirm" data-dismiss="modal">Delete</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
