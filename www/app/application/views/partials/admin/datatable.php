<div class="table-responsive">
    <table id="datatable" class="<%= cls %> table">
        <thead>
        <tr>
            <% _.each(columns, function (column, index) { %>
                <th class="<%= column.mDataProp %>"><%= column.title %></th>
            <% }) %>
        </tr>
        </thead>
    </table>
</div>