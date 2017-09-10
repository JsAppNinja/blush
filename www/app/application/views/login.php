<div class="body login">
    <div class="container">

        <? if($this->session->flashdata('inactive')) { ?>
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <div class="alert alert-danger">
                    <p>Your account has not yet been approved by your parent/guardian.  In order for your account to be approved, one of
                        your parent/guardians needs to click the confirmation link provided them in the notification email we sent when you registered.</p>
                    <p><strong>If you would like to resend the confirmation email,
                            <a href="<?=site_url('accounts/resend_confirmation/'.$this->session->flashdata('inactive'))?>">
                                click here.
                            </a>
                        </strong></p>
                </div>
            </div>
        </div>
        <? } ?>

        <? if($this->session->flashdata('success')) { ?>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2"></div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="alert alert-success"><?= $this->session->flashdata('success')?></div>
            </div>
        </div>
        <? } ?>

        <div class="row">
            <div class="col-lg-4 col-md-2 col-sm-2"></div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div id="login-box">
                    <form method="post">
                        <div class="form-group username">
                            <label class="sr-only" for="login-username">Username</label>
                            <span class="prefix"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" id="login-username" placeholder="username" name="username">
                        </div>

                        <div class="form-group password">
                            <label class="sr-only" for="login-username">Password</label>
                            <span class="prefix"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" id="login-password" placeholder="password" name="password">
                        </div>

                        <div class="clearfix"></div>

                        <p>
                            <small>
                                <a href="<?=site_url('login/forgot')?>">forgot password?</a>
                            </small>
                        </p>

                        <button class="pull-right btn btn-primary btn-lg">Signin <i class="glyphicons chevron-right white"></i></button>
                        <div class="clearfix"></div>
                    </form>
                </div>


                <div class="clearfix"></div>
                <? if($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error')?></div>
                <? } ?>

                <div class="clearfix"></div>

            </div>

        </div>
    </div>
</div>