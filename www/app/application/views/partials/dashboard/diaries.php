<div class="row">
<% if(_.size(objects)>0) { %>
    <% _.each(objects, function(diary, index, list) {  %>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="diary <% if(parseInt(diary.read) === 0) {%>unread<% } %>" data-id="<%=diary.uuid%>">

                <% if(diary.comments && parseInt(diary.comments_read) < 1) { %>
                    <span class="label label-pink">new comments</span>
                <% } %>
                <% if(diary.draft > 0) { %>
                    <span class="label label-info">draft</span>
                <% } %>
                <h5><%= diary.title%></h5>
                <p><%= $.format.date(diary.created, "MM/dd/yyyy") %></p>
            </div>
        </div>

        <% if((index+1) % 4 == 0) { %></div><div class="row"><% } %>
    <% }); %>
<% } else { %>
<h3 class="muted">There are no journals currently.</h3>
<% } %>
</div>