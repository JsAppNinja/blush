<form class="std-form">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h4>Add Video Session</h4>
                <p>Video sessions must be schedule at least 24 hours in advance and cannot be cancelled within 24 hours.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 alert-holder"></div>
        </div>
        <div class="row">
            <div class="col-lg-6">

                <div class="form-group date with-label">
                    <label for="session_date">Video Session Date</label>
                    <input type="text" class="form-control datepicker" id="session_date" placeholder="Video Session Date"
                           name="session_date" tabindex="1" data-rule-required="true">
                    <i class="glyphicons calendar"></i>
                </div>

                <div class="form-group with-label">
                    <label for="session_date">Time of Session</label>
                    <?= form_hour('session_time', '10:00:00', 'class="form-control"') ?>
                </div>
            </div>
            <div class="col-lg-6">
                <% if(availability.timeslots.length>0) { %>
                    <h4>Coach Availability</h4>
                    <p>Please choose a date on the left to see your coach's availability for that date:</p>
                    <div class="additional-availability"></div>
                <% } %>
            </div>
        </div>
    </div>

    <div class="form-group submit text-center">
        <button id="video-add-submit" class="btn btn-lg btn-primary" data-loading-text="Scheduling Session...">Submit</button>
    </div>
</form>