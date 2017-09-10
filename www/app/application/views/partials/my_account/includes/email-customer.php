<% if(app.user.user_type_id == app.user_type_customer) { %>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_message" id="email_message" <% if(parseInt(email_message)>0) {%>checked="checked"<% } %>/>
                When I receive new messages from my coach.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_diary" id="email_diary" <% if(parseInt(email_diary)>0) {%>checked="checked"<% } %>/>
                When a diary I've submitted has been sent to my coach.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_reminder" id="email_reminder" <% if(parseInt(email_reminder)>0) {%>checked="checked"<% } %>/>
                When I have an upcoming video session with my coach.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_purchase" id="email_purchase" <% if(parseInt(email_purchase)>0) {%>checked="checked"<% } %>/>
                When I have a successfully added credits to my account.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_general" id="email_general" <% if(parseInt(email_general)>0) {%>checked="checked"<% } %>/>
                Important updates and promotions.
            </label>
        </div>
    </div>
</div>
<% } %>