<div class="row">
    <div class="col-lg-12">
        <h3>Notification: <%= name %></h3>
    </div>
</div>
<div class="row">
    <form action="" method="post" id="notification-form" class="std-form">
    <div class="col-lg-12">
        <h4>Macros</h4>
        <p>The following macros can be used to insert dynamic values into the email.  They are available in all email notification templates</p>

        <div class="row">
            <div class="col-lg-6">
                <dl>
                    <dt>{COACH}</dt>
                    <dd>The Coach associated with the account. <br/>
                        <small><em>Fields: NAME, FIRSTNAME, LASTNAME, EMAIL, PHONE, GENDER, ADDRESS</em></small></dd>
                </dl>
            </div>
            <div class="col-lg-6">
                <dl>
                    <dt>{CLIENT}</dt>
                    <dd>The Client associated with the account. <br/>
                        <small><em>Fields: NAME, FIRSTNAME, LASTNAME, EMAIL, PHONE, GENDER, ADDRESS, CREDITS</em></small></dd>
                </dl>
            </div>

        </div>
    </div>
    </form>
</div>
<div class="row">
    <div class="col-lg-12">
        <textarea name="body" style="display:none" class="form-control" id="notification-body"><%=body%></textarea>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 submit-container">
        <button class="submit btn btn-primary pull-right btn-md" data-loading-text="Saving..." tabindex="15">Save</button>
        <button class="test btn pull-left btn-md" data-toggle="modal" data-target="#test_notification_modal">Send Test</button>
        <button class="cancel btn pull-right btn-md">Cancel</button>
        <div class="alert alert-success pull-right" style="display:none"></div>
        <div class="alert alert-danger pull-right" style="display:none"></div>
    </div>
</div>
<div class="modal fade" id="test_notification_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Send Test Notification</h4>
            </div>
            <div class="modal-body">
                <p>If you would like to test this notification, enter an email address or list of email addresses delimited by a comma below.
                The system will load dummy objects and send them to test your email.</p>
                <div class="form-group">
                    <label>Email(s):</label>
                    <input type="text" name="test-emails" id="test-emails" class="form-control"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" data-loading-text="Sending Test..." class="btn btn-primary btn-test">Send</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->