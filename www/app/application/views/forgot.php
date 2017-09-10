<div class="body login">
    <div class="container">

        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div id="login-box" class="forgot">
                    <p>If you have forgotten your password, enter your username or email address below and we will reset the password and email it to
                    the email address we have associated with your account.</p>

                    <form method="post">
                        <div class="form-group username">
                            <label class="sr-only" for="login-username">Username or Email</label>
                            <span class="prefix"><i class="glyphicons user white"></i></span>
                            <input type="text" class="form-control" id="login-username" placeholder="Username or Email" name="username_email">
                        </div>

                        <div class="clearfix"></div>

                        <button class="pull-right btn btn-primary btn-lg">Email Password <i class="glyphicons envelope white"></i></button>
                        <div class="clearfix"></div>
                    </form>
                </div>


                <div class="clearfix"></div>
                <? if($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error')?></div>
                <? } ?>

            </div>

        </div>
    </div>
</div>