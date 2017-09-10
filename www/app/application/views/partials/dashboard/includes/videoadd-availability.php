<% if(model.timeslots && model.timeslots.length>0) { %>
    <div class="well well-pink">
        <strong> Your coach has the following availability on <%=session_date%></strong>
        <% _.each(model.timeslots, function(timeslot) { %>
            <ul>
                <li><%=timeslot.timespan%></li>
            </ul>
        <% }) %>
    </div>
<% } else { %>
    <div class="alert alert-danger">
        <p>Your coach is not available on this date.</p>
    </div>
<% } %>