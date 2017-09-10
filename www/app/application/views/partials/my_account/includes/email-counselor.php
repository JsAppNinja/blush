<% if(app.user.user_type_id == app.user_type_counselor) { %>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_message" id="email_message" <% if(parseInt(email_message)>0) {%>checked="checked"<% } %>/>
                When I receive new messages from one of my clients.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_diary" id="email_diary" <% if(parseInt(email_diary)>0) {%>checked="checked"<% } %>/>
                When one of my clients has submitted a new diary.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_reminder" id="email_reminder" <% if(parseInt(email_reminder)>0) {%>checked="checked"<% } %>/>
                When I have an upcoming video session with one of my clients.
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" value="1" name="email_purchase" id="email_purchase" <% if(parseInt(email_purchase)>0) {%>checked="checked"<% } %>/>
                When I have been paid.
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