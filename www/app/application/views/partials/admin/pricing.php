
<form action="" method="post" id="notification-form" class="std-form">
    <div class="row">
        <div class="col-lg-12">
            <h3>Blush Journal</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <label>Price</label>
            <div class="form-group">
                <input type="text" class="form-control money" id="blush_journal" placeholder="Blush Journal" name="blush_journal"
                       tabindex="1" value="<%= accounting.formatMoney(blush_journal) %>" data-rule-required="true">
            </div>
        </div>
        <div class="col-lg-6">
            <label>Discount Price</label>
            <div class="form-group">
                <input type="text" class="form-control money" id="blush_journal_discount" placeholder="Discount Price" name="blush_journal_discount"
                       tabindex="2" value="<%= accounting.formatMoney(blush_journal_discount) %>" data-rule-required="true">
            </div>
            <div class="form-group">
                <label for="firstname">
                <input type="checkbox" id="blush_journal_discount_use" name="blush_journal_discount_use" tabindex="3" value="1"
                    <% if(blush_journal_discount_use>0) { %>checked="checked"<% } %>/> Use Discount?
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="row">
            <div class="col-lg-12">
                <h3>Blush Video</h3>
            </div>
        </div>
        <div class="col-lg-6">
            <label>Price</label>
            <div class="form-group">
                <input type="text" class="form-control money" id="blush_video" placeholder="Blush Video" name="blush_video"
                       tabindex="4" value="<%= accounting.formatMoney(blush_video) %>" data-rule-required="true">
            </div>
        </div>
        <div class="col-lg-6">
            <label>Discount Price</label>
            <div class="form-group">
                <input type="text" class="form-control money" id="blush_video_discount" placeholder="Discount Price" name="blush_video_discount"
                       tabindex="5" value="<%= accounting.formatMoney(blush_video_discount) %>" data-rule-required="true">
            </div>
            <div class="form-group">
                <label for="firstname">
                    <input type="checkbox" id="blush_video_discount_use" name="blush_video_discount_use" tabindex="6" value="1"
                           <% if(blush_video_discount_use>0) { %>checked="checked"<% } %>/> Use Discount?
                </label>
            </div>
        </div>
    </div>

    <hr/>

    <p>To update the discount price and credits of each plan, use the fields below.  At this time, Stripe does not allow us
    to change the price of existing plans.</p>

    <% _.each(plans, function(plan, iterator) { %>
    <div class="row">
        <div class="col-lg-12">
            <h3><%=plan.name%></h3>
            <input type="text" name="name_<%=iterator%>" value="<%=plan.name%>" style="display:none"/>
            <input type="text" name="id_<%=iterator%>" value="<%=plan.id%>" style="display:none"/>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <label>Price</label>
            <input type="text" class="form-control money" id="price_<%=iterator%>" placeholder="Price" name="price_<%=iterator%>"
                   value="<%= accounting.formatMoney(plan.price) %>" disabled="disabled" data-rule-required="true">
        </div>
        <div class="col-lg-4">
            <label>Discount Price</label>
            <div class="form-group">
                <input type="text" class="form-control money" id="discount_price_<%=iterator%>" placeholder="Discount Price" name="discount_price_<%=iterator%>"
                       value="<%= accounting.formatMoney(plan.discount_price) %>" data-rule-required="true">
            </div>
            <div class="form-group">
                <label for="firstname">
                    <input type="checkbox" id="use_discount_price_<%=iterator%>" name="use_discount_price_<%=iterator%>" value="1"
                           <% if(plan.use_discount_price>0) { %>checked="checked"<% } %>/> Use Discount?
                </label>
            </div>
            <label>Discount Code</label>
            <div class="form-group">
                <input type="text" class="form-control" id="coupon_code_<%=iterator%>" placeholder="Discount Code" name="coupon_code_<%=iterator%>"
                       value="<%= plan.coupon_code %>">
            </div>
        </div>
        <div class="col-lg-4">
            <label>Credits</label>
            <input type="text" class="form-control" id="credits_<%=iterator%>" placeholder="Credits" name="credits_<%=iterator%>"
                   value="<%= plan.credits %>" data-rule-required="true">
        </div>
    </div>

    <hr/>
    <% }); %>
</form>

<div class="row">
    <div class="col-lg-12 submit-container">
        <button class="submit btn btn-primary pull-right btn-md" data-loading-text="Saving..." tabindex="15">Save</button>
        <div class="alert alert-success pull-right" style="display:none"></div>
        <div class="alert alert-danger pull-right" style="display:none"></div>
    </div>
</div>