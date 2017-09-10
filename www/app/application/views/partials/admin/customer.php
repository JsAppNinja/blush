<% if(!counselor) { %>
    <div class='alert alert-warning row'>
        <div class="col-lg-9">
            <p>This customer currently is not assigned to a counselor.  Click the "Assign Coach" to assign this user
            to a counselor.</p>
            <% if(registration.data.preferred_coach) { %>
            <p><strong>This customer has requested: <%=registration.data.preferred_coach%></strong></p>
            <% } %>
        </div>
        <div class="col-lg-3 text-right">
            <button data-toggle="modal" data-target="#assign_counselor_modal" class="assign_counselor btn btn-primary" data-loading-text="Assigning Coach...">Assign Coach</button>
        </div>
    </div>
<% } else { %>
    <div class='alert alert-warning row'>
        <p class="col-lg-9">Coach: <strong><%= counselor.firstname + " " + counselor.lastname %></strong></p>
        <div class="col-lg-3 text-right">
            <button data-toggle="modal" data-target="#assign_counselor_modal" class="assign_counselor btn btn-primary" data-loading-text="Changing Coach...">Reassign Coach</button>
        </div>
    </div>
<% } %>
<ul class="nav nav-tabs">
    <li class="active"><a href="#profile" data-toggle="tab">Profile Settings</a></li>
    <% if(registration.data) { %>
        <li><a href="#registration" data-toggle="tab">Registration Data</a></li>
    <% } %>
</ul>

<div class="tab-content">
    <? include(APPPATH.'views/partials/admin/user/profile.php'); ?>
    <? include(APPPATH.'views/partials/admin/user/registration-data.php'); ?>
</div>

<div class="modal fade" id="assign_counselor_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Assign Coach</h4>
            </div>
            <div class="modal-body">
                <select id="counselor_id" name="counselor_id" class="form-control">
                    <? $counselors = $this->User->get_counselors() ?>
                    <? foreach($counselors as $counselor) { ?>
                        <option value="<?=$counselor->id?>"><?=$counselor->firstname." ".$counselor->lastname?></option>
                    <? } ?>
                </select>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Assign</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
