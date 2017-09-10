<form action="" method="post">
    <div class="col-lg-12">
        <div class="row top">
            <div class="col-lg-7 col-md-6 col-sm-6">
                <h4>Messages</h4>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-6 text-right">

                <!--
                <div class="btn-group">
                    <button type="button" class="btn btn-yellow dropdown-toggle" data-toggle="dropdown">
                        Actions <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Action</a></li>
                    </ul>
                </div>-->

                <button class="btn btn-blue new-message">New Message</button>
                <!--
                <button class="btn btn-lt-brown"><i class="glyphicon glyphicon-search"></i></button>
                -->
            </div>
            <div class="clearfix"></div>
        </div>
        <% if(typeof conversation_uuid != undefined && conversation_uuid) { %>
            <div class="row">
                <div class="col-lg-12">
                    <textarea name="text" id="text" class="reply form-control input-block-level" data-rule-required="true" placeholder="Compose a New Message"></textarea>
                </div>
            </div>
        <% } %>
        <% if(typeof conversation_uuid != undefined && conversation_uuid) { %>
            <div class="row">
                <div class="col-lg-12">
                    <div class="submit-container">
                        <div class="pull-right">
                            <button class="submit btn btn-md btn-primary" data-loading-text="Sending...">Send</button>
                        </div>

                        <div class="alert alert-success pull-right" style="display:none"></div>
                        <div class="alert alert-danger pull-right" style="display:none"></div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        <% } %>

        <% if (typeof objects != "undefined") { %>
            <% _ . each(objects, function (message, index, messages) { %>
                <div class="message" data-id="<%=message.uuid%>">
                <div class="row">
                    <div class="col-lg-2 hidden-xs">
                        <img class="img-circle img-thumbnail" src="<%=message.sender_picture%>"/>
                    </div>

                    <div class="col-lg-10">
                        <h3 class="pull-left"><%=message.sender%></h3>
                        <span class="date pull-right"><%=message.created%></span>
                        <div class="clearfix"></div>

                        <div class="content">
                            <h5><%=message.title%></h5>
                            <%=message.text%>
                        </div>
                    </div>
                </div>

            </div>
            <% }); %>
        <% } %>


    </div>


</form>