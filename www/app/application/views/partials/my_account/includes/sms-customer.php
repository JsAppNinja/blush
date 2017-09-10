<% if(app.user.user_type_id == app.user_type_customer) { %>
<h4>SMS Settings</h4>
<p>If you would like to receive SMS notifications on you mobile phone, you can elect to do so by selecting the various notification topics below. Please be aware that standard Text Message rates will apply from your mobile service provider.</p>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="sms_message" id="sms_message" <% if(parseInt(sms_message)>0) {%>checked="checked"<% } %>/>
                When I receive new messages from my coach.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="sms_diary" id="sms_diary" <% if(parseInt(sms_diary)>0) {%>checked="checked"<% } %>/>
                When a diary I've submitted has been sent to my coach.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="sms_reminder" id="sms_reminder" <% if(parseInt(sms_reminder)>0) {%>checked="checked"<% } %>/>
                When I have an upcoming video session with my coach.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="sms_purchase" id="sms_purchase" <% if(parseInt(sms_purchase)>0) {%>checked="checked"<% } %>/>
                When I have a successfully added credits to my account.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="sms_general" id="sms_general" <% if(parseInt(sms_general)>0) {%>checked="checked"<% } %>/>
                Important updates and promotions.
            </label>
        </div>
    </div>
</div>
<% } %>