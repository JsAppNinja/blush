<div class="row">
    <div class="col-lg-12">
        <h3>Transaction Details</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-7">
        <div class="row">
            <div class="col-lg-5">
                <label>Created:</label>
            </div>
            <div class="col-lg-7">
                <span><%=created%></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <label>Transaction #:</label>
            </div>
            <div class="col-lg-7">
                <span><%=transaction_nbr%></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <label>Stripe #:</label>
            </div>
            <div class="col-lg-7">
                <span><%=stripe_id%></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <label>Amount:</label>
            </div>
            <div class="col-lg-7">
                <span><%=amount%></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <label>Blush Journal Credits:</label>
            </div>
            <div class="col-lg-7">
                <span><%=diary_cnt%></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <label>Counseling Credits:</label>
            </div>
            <div class="col-lg-7">
                <span><%=counseling_cnt%></span>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="row">
            <div class="col-lg-5">
                <label>Customer:</label>
            </div>
            <div class="col-lg-7">
                <a class="customer" href="#" data-uuid="<%=customer_uuid%>"><%=customer%></a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <label>Coach:</label>
            </div>
            <div class="col-lg-7">
                <a class="counselor" href="#" data-uuid="<%=counselor_uuid%>"><%=counselor%></a>
            </div>
        </div>

    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-lg-12 submit-container">
            <button class="back btn pull-right btn-md">Back</button>
            <div class="alert alert-success pull-right" style="display:none"></div>
            <div class="alert alert-danger pull-right" style="display:none"></div>
        </div>
    </div>
</div>