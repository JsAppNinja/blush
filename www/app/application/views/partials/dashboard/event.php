<div class="col-lg-12">
<form action="" method="post">
    <div class="date-title">
        <div class="row">
            <div class="col-lg-1 col-md-2 col-sm-2">
                <span class="day"><%=day%></span>
            </div>
            <div class="col-lg-11 col-md-10 col-sm-10">
                <div class="text">
                    <h4><%=title%></h4>
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
                <div class="text">Event Details</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 content">
            <div>
            <% if(text) { %>
                <p><%=text%></p>
            <% } else { %>
                <p>There are no details for this event.</p>
            <% } %>
            </div>
        </div>
    </div>
</form>
</div>