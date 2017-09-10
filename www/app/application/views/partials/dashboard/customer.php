<div class="row">
    <div class="col-lg-3">
        <img class="img-circle img-thumbnail" src="<%=picture%>"/>

        <div class="name text-left">
            <%=firstname+" "+lastname%>
            <div class="dropper">
                <div class="icon icon-notes" style="display:none">
                    <span class="glyphicons notes"></span>
                </div>
                <div class="icon icon-diary" style="display:none">
                    <span class="glyphicons book"></span>
                </div>
                <div class="icon icon-info" style="display:none">
                    <span class="glyphicons circle_info"></span>
                </div>
            </div>

            <ul class="dropper-menu list-unstyled" style="display:none">
                <li class="info">
                    <div class="icon icon-info">
                        <span class="glyphicons circle_info"></span>
                    </div>
                    <span class="title">Customer Info</span>
                </li>
                <li class="notes">
                    <div class="icon icon-notes">
                        <span class="glyphicons notes"></span>
                    </div>
                    <span class="title">Notes</span>
                </li>
                <li class="diary">
                    <div class="icon icon-diary">
                        <span class="glyphicons book"></span>
                    </div>
                    <span class="title">Blush Journals</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-lg-9">
        <div id="item-list"></div>
    </div>
</div>