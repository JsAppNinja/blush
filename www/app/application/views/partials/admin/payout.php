<div class="row">
    <div class="col-lg-12">
        <h3>Payable: <%= stripe_transfer_id %></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <p><label>Coach: </label><br/><%=firstname+" "+lastname%></p>
        <p><label>Date: </label><br/><%=created%></p>
    </div>
    <div class="col-lg-4">
        <div class="stat">
            <h4>Transactions</h4>
            <div class="count"><%= cnt %></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="stat">
            <h4>Amount</h4>
            <div class="count"><%= accounting . formatMoney(amount) %></div>
        </div>
    </div>
</div>

<p>&nbsp;</p>

<div class="row">
    <div class="col-lg-12 submit-container">
        <button class="back btn pull-right btn-md">Back</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h5>Transactions included in this Payout:</h5>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <table id="datatable">
            <thead>
                <tr>
                    <% _ . each(columns, function (column, index) { %>
                        <th><%= column . title %></th>
                    <% }) %>
                </tr>
            </thead>
        </table>
    </div>
</div>