<div class="row">
    <div class="col-lg-3">
        <div class="stat">
            <h4>Coaches</h4>
            <div class="count"><%=counselors%></div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="stat">
            <h4>Customers</h4>
            <div class="count"><%=customers%></div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="stat">
            <h4>Video Sessions</h4>
            <div class="count"><%=events%></div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="stat">
            <h4>Transactions</h4>
            <div class="count"><%=transactions%></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <h5>Last 30 Days</h5>

        <ul class="stats list-unstyled">
            <li>Customer Signups <strong><%=last_30_customers%></strong></li>
            <li>Blush Journal Submissions <strong><%=last_30_diaries%></strong></li>
            <li>Video Sessions <strong><%=last_30_videos%></strong></li>
            <li>Transactions <strong><%=last_30_transactions%></strong></li>
            <li>Money Spent <strong><%=accounting.formatMoney(last_30_money)%></strong></li>
        </ul>
    </div>
</div>

<h1 id="page-title">System Settings/Control</h1>

<form method="post">
    <div class="spacer"></div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Scheduling</h4></div>
                <div class="panel-body">

                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="prevent_schedule_24hour" class="config-value" value="1" <% if(config.prevent_schedule_24hour==1) { %>checked="checked"<% } %>/> Prevent Scheduling Events Less than 24 hours in advance
                        </label>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 submit-container">
            <button class="submit btn btn-primary pull-right btn-md" data-loading-text="Saving..." tabindex="15">Save</button>
            <button class="cancel btn pull-right btn-md">Cancel</button>
            <div class="alert alert-success pull-right" style="display:none"></div>
            <div class="alert alert-danger pull-right" style="display:none"></div>
        </div>
    </div>
</form>
