<form action="" method="post">
    <div class="title">
        <div class="row">
            <div class="icon col-lg-1 col-md-2 col-xs-2">
                <i class="glyphicons notes"></i>
            </div>
            <div class="col-lg-11  col-md-10 col-xs-10">
                <h2>Notes</h2>
            </div>
        </div>
    </div>

    <% if (typeof objects != "undefined") { %>
        <% _ . each(objects, function (note, index, messages) { %>
            <div class="note" data-id="<%=note.uuid%>">
                <div class="row">
                    <div class="col-lg-12">
                        <%=note.text%>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 meta">
                        <%=note.created%>
                    </div>
                </div>

            </div>
        <% }); %>
    <% } %>

    <div class="row">
        <div class="col-lg-12">
            <textarea name="text" id="text" class="reply form-control input-block-level" data-rule-required="true" placeholder="Add a New Note"></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="submit-container">
                <div class="pull-right">
                    <button class="submit btn btn-md btn-primary" data-loading-text="Saving...">Add Note</button>
                </div>

                <div class="alert alert-success pull-right" style="display:none"></div>
                <div class="alert alert-danger pull-right" style="display:none"></div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</form>