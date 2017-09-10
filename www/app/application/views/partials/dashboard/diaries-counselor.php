<% if(_.size(objects)>0) { %>
<% _.each(objects, function(diary, index, list) {  %>
<div class="diary-counselor" data-uuid="<%= diary.uuid%>" data-read="<%=diary.read%>">
    <div class="title">
        <div class="row">

            <div class="col-lg-1 col-md-2 col-xs-2">
                <div class="icon">
                    <span></span>
                </div>
            </div>
            <div class="col-lg-11 col-md-10 col-xs-10">
                <div class="text"><%= diary.title%></div>
                <% if(parseInt(diary.read) === 0) { %>
                    <span class="label label-pink">new</span>
                <% } %>
            </div>
        </div>
    </div>

    <div class="body-comments" style="display:none">
        <div class="body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="datetime"><%= $.format.date(diary.created, "MM/dd/yyyy") %></div>
                    <div class="text"><%= diary.text%></div>
                </div>
            </div>
        </div>

        <form name="diary-comments-<%=diary.uuid%>">
            <div class="comments">
                <div class="title">
                    <h4>Comments</h4>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <textarea name="comments" class="form-control input-block-level"><%=diary.comments%></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="submit-container">
                            <div class="pull-right">
                                <button type="button" data-uuid="<%= diary.uuid%>" class="submit btn btn-md" data-loading-text="Saving...">Save</button>
                            </div>

                            <div class="alert alert-success pull-right" style="display:none"></div>
                            <div class="alert alert-danger pull-right" style="display:none"></div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<% }); %>
<% } else { %>
    <h3 class="muted">There are no journals currently.</h3>
<% } %>