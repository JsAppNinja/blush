<form action="" method="post">
    <div class="title">
        <div class="row">

            <div class="col-lg-1 col-md-2 col-sm-2">
                <div class="icon">
                    <span></span>
                </div>
            </div>
            <div class="col-lg-11 col-md-10 col-sm-10">
                <input type="text" placeholder="Your Title Here" class="form-control" name="title" id="title" value="<%=title%>" data-rule-required="true"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <textarea name="text" id="text" class="form-control input-block-level text"><%=text%></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="submit-container">
                <% if((draft===1)) { %>
                <div class="pull-left">
                    <span class="word-count"></span>
                </div>
                <div class="pull-right">
                    <button type="button" class="cancel btn btn-md">Cancel</button>
                    <button type="button" class="save btn btn-md" data-loading-text="Saving...">Save as Draft</button>
                    <button type="button" class="submit btn btn-md" data-loading-text="Sending...">Send to Coach</button>
                </div>
                <% } else { %>
                <div class="pull-left">
                    <button class="cancel btn btn-md">Close</button>
                </div>

                <% } %>

                <div class="alert alert-success pull-right" style="display:none"></div>
                <div class="alert alert-danger pull-right" style="display:none"></div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <% if(comments) { %>
    <div class="comments">
        <div class="title">
            <h4>Comments</h4>
        </div>

        <div class="row">
            <div class="col-lg-2">
                <img class="img-circle img-thumbnail" src="<%=commenter_picture%>"/>
            </div>
            <div class="col-lg-10">
                <div class="text"><%=comments%></div>
            </div>
        </div>
    </div>
    <% } %>
</form>