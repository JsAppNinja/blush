<? $user = get_user() ?>
<form action="" method="post">
    <div class="title">

        <? if(isset($user->user_type_id) && $user->user_type_id==USER_TYPE_CUSTOMER) { ?>
        <div class="row">
            <div class="icon col-xs-2 ">
                <i class="glyphicons message_plus"></i>
            </div>
            <div class="col-xs-10">
                <input type="text" placeholder="Your Title Here" class="form-control" name="title" id="title" value="<%=title%>" data-rule-required="true"/>
            </div>
        </div>
        <? } else if(isset($user->id)) { ?>
        <div class="row recipient">
            <div class="icon col-lg-1 col-xs-3">
                <i class="glyphicons message_plus"></i>
            </div>
            <div class="col-lg-11 col-xs-9 recipient-select">
                <?= form_customer($user->id, 'recipient', '', 'class="form-control" data-rule-required="true" placeholder="Choose Recipient"', true); ?>
            </div>
        </div>
        <div class="row">
           <div class="col-lg-12">
                <input type="text" placeholder="Your Title Here" class="form-control" name="title" id="title" value="<%=title%>" data-rule-required="true"/>
                <div class="clearfix"></div>
            </div>
        </div>
        <? } ?>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <textarea name="text" id="text" class="form-control input-block-level" data-rule-required="true"><%=text%></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="submit-container">
                <div class="pull-right">
                    <button class="cancel btn btn-md">Back to Messages</button>
                    <button class="submit btn btn-md btn primary" data-loading-text="Sending...">Send</button>
                </div>

                <div class="alert alert-success pull-right" style="display:none"></div>
                <div class="alert alert-danger pull-right" style="display:none"></div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</form>