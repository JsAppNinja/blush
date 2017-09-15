<div class="alert-container"></div>
<div class="col-lg-12">
<% if (_ . size(objects) > 0) { %>
    <h3>Upcoming Events</h3>
    <% _ . each(objects, function (event, index, list) { %>
        <div class="event">
            <div class="date-title">
                <div class="row">
                    <div class="col-lg-1 col-md-2 col-sm-2">
                        <span class="month"><%= event.month %></span>
                        <span class="day"><%= event . day %></span>
                    </div>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                        <div class="text">
                            <h4><%= event . title %></h4>
                            <h5>One Time Event</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="title">
                <div class="row">
                    <div class="col-lg-1 col-md-2 col-sm-2">
                        <div class="icon">
                            <span></span>
                        </div>
                    </div>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                        <div class="text pull-left">Event Details</div>
                        <%= app.user %>
                        <div title="Add to Calendar" class="addeventatc">
                            Add to Calendar
                            <span class="start">09/29/2017 08:00 AM</span>
                            <span class="end">09/29/2017 10:00 AM</span>
                            <span class="timezone">America/Chicago</span>
                            <span class="title">Summary of the event</span>
                            <span class="description">Description of the event</span>
                            <span class="location">Location of the event</span>
                            <span class="date_format">MM/DD/YYYY</span>
                            <span class="client">axHkeGoayzZGWlMVvmiE28885</span>
                        </div>
                        <button class="cancel btn btn-sm pull-right btn-danger" data-uuid="<%=event.uuid%>" data-loading-text="Cancelling Event...">
                            <i class="fa fa-remove"></i> Cancel Event
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 content">
                    <div class="alert-danger alert" style="display:none"></div>
                    <div>
                    <% if (event . text) { %>
                        <p><%= event . text %></p>
                    <% } else { %>
                        <p>There are no details for this event.</p>
                    <% } %>
                    </div>
                </div>
            </div>
        </div>
    <% }); %>
    <% } else { %>
    <h3 class="muted">There are no upcoming events currently scheduled.</h3>
<% } %>
</div>