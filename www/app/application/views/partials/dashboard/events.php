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
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="text">
                            <h4><%= event . title %></h4>
                            <h5>One Time Event</h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-2 col-sm-2">
                        <!-- 2. Include script -->
                            <script type="text/javascript">(function () {
                                    if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
                                    if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
                                        var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                        s.type = 'text/javascript';s.charset = 'UTF-8';s.async = true;
                                        s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
                                        var h = d[g]('body')[0];h.appendChild(s); }})();
                            </script>

                            <!-- 3. Place event data -->
                            <span class="addtocalendar atc-base pull-right">
                                <var class="atc_event">
                                    <var class="atc_date_start">2017-05-04 12:00:00</var>
                                    <var class="atc_date_end">2017-05-04 18:00:00</var>
                                    <var class="atc_timezone">Europe/London</var>
                                    <var class="atc_title">Star Wars Day Party</var>
                                    <var class="atc_description">May the force be with you</var>
                                    <var class="atc_location">Tatooine</var>
                                    <var class="atc_organizer">Luke Skywalker</var>
                                    <var class="atc_organizer_email">luke@starwars.com</var>
                                </var>
                            </span>
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