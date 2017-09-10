<div class="spacer"></div>
<h3>Coaching Availability</h3>

<p>If you would like to specify to your clients what periods you are available for coaching sessions, enter the
    dates/times below that work for you each week.</p>
<div class="alert-container"></div>
<h4>Current Availability</h4>

<table class="table">
    <thead>
    <th>Day</th>
    <th>Start</th>
    <th>End</th>
    <th></th>
    </thead>
    <tbody>
    <% _.each(availability.timeslots, function(day, iterator) { %>
        <tr data-id="<%=day.id%>">
            <td><%=day.day_of_week%></td>
            <td><%=day.pretty_start_time%></td>
            <td><%=day.pretty_end_time%></td>
            <td><button class="btn btn-xs pull-right btn-danger remove">Delete</button></td>
        </tr>
    <% }) %>
    </tbody>
</table>

<hr/>

<h4>Add Days/Times</h4>

<form action="" method="post" id="profile-form" class="std-form">
    <div class="availability-row">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="day">Day of the Week</label>
                    <?=form_day_of_week('day', '', 'class="form-control" placeholder="Day of the week" data-rule-required="true"')?>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <?=form_hour('start_time', '', 'class="form-control"  data-rule-required="true"')?>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <?=form_hour('end_time', '', 'class="form-control"  data-rule-required="true"')?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 submit-container">
                <button class="add btn btn-primary pull-right btn-lg" data-loading-text="Saving...">Add</button>
                <div class="alert alert-success pull-right" style="display:none"></div>
                <div class="alert alert-danger pull-right" style="display:none"></div>
            </div>
        </div>
    </div>
</form>