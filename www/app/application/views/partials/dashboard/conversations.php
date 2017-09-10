
<div class="col-lg-3 col-md-3 col-sm-4 hidden-xs" id="conversation-list">
    <% _.each(objects, function(conversation, index, conversations) { %>
        <div class="row conversation <% if(index%2==0) { %>even<% } %>" data-id="<%=conversation.uuid%>">
            <div class="col-lg-3">
                <img class="img-circle img-thumbnail" src="<%=conversation.customer_picture%>"/>
                <% if(conversation.new_message_count > 0) { %><span class="badge badge-pink"><%=conversation.new_message_count%></span><% } %>
            </div>
            <div class="details col-lg-9">
                <h4 class="pull-left"><%=conversation.customer%></h4>
                <span class="date pull-right"><%=conversation.modified%></span>
                <div class="clearfix"></div>
                <p class="excerpt"><%=conversation.excerpt%></p>
            </div>
        </div>
    <% }); %>
</div>
<div class="visible-xs col-xs-12">
    <select name="conversations" class="form-control" id="conversation-selector">
        <% _.each(objects, function(conversation, index, conversations) { %>
            <option value="<%=conversation.uuid%>"><%=conversation.customer%></option>
        <% }); %>
    </select>
</div>
<div class="col-lg-9 col-md-9 col-sm-8" id="message-list"></div>
<div class="clearfix"></div>

