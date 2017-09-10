<% if (_.size(objects) > 0) { %>
    <% _.each(objects, function (customer, index, list) { %>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="customer text-center" data-id="<%= customer.uuid %>">
                <img class="img-circle img-thumbnail" src="<%= customer.picture %>"/>

                <div class="name text-center">
                    <% if (customer.has_unread > 0) { %>
                        <span class="label label-pink">new</span>
                    <% } %>
                    <%= customer.firstname + " " + customer.lastname %>
                </div>
            </div>
        </div>
    <% }); %>
    <% } else { %>
    <div class="col-lg-12">
        <h3 class="muted">There are no customers currently.</h3>
    </div>
<% } %>