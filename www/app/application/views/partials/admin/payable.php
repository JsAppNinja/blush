<%= account %>
<% if (!account) { %>
    <div class='alert alert-warning row'>
        <p class="col-lg-9">This counselor has not added a bank account to the site and cannot be paid.  If you want to
            email them, click the email button to the right to notify them that they cannot be paid currently.
        </p>
        <div class="col-lg-3 text-right">
            <a href="mailto:<%= email %>" class="assign_counselor btn btn-primary">Email Coach</a>
        </div>
    </div>
<% } %>
<div class="row">
    <div class="col-lg-12">
        <h3>Pay Coach: <%= firstname + " " + lastname %></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <% if (account) { %>
            <p><label>Bank: </label><br/><<%=account.bank_name%><br/>

                <label>Account # Last 4: </label><br/><%=account.last4%><br/>
                <label>Stripe Verified?: </label><br/><%=account.verified%>
            </p>
        <% } else { %>
            <p>No banking account set.</p>
        <% } %>

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
        <% if (account) { %>
            <button class="btn btn-primary btn-md pay pull-right" data-loading-text="Submiting Payment...">Submit Payment</button>
        <% } %>
        <button class="back btn pull-right btn-md">Back</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h5>Transactions to be Paid:</h5>
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