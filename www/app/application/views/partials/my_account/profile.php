<ul class="nav nav-pills">
    <li class="active"><a href="#home" data-toggle="tab">Profile</a></li>
    <li><a href="#mood" data-toggle="tab">Mood/Feeling</a></li>
    <li><a href="#history" data-toggle="tab">Counseling History</a></li>
    <li><a href="#know" data-toggle="tab">Get To Know You</a></li>
    <li><a href="#personality" data-toggle="tab">Your Personality</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="home">
        <form action="" method="post" id="profile-form" class="std-form">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="sr-only" for="firstname">First Name</label>
                        <input type="text" class="form-control" id="firstname" placeholder="First Name" name="firstname"
                               tabindex="1" value="<%= firstname %>">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="email">Email</label>
                        <input type="text" class="form-control" id="email" placeholder="Email" name="email" tabindex="3"
                               value="<%= email %>">
                    </div>
                    <div class="form-group date">
                        <label class="sr-only" for="birthday">Birthday</label>
                        <input type="text" class="form-control datepicker" id="birthday" placeholder="Birthday" name="birthday"
                               tabindex="5" value="<%= birthday %>">
                        <i class="glyphicons calendar"></i>
                    </div>

                    <div class="profile-pic">
                        <h5>Profile Picture</h5>
                        <img src="<?= get_avatar(IMG_SIZE_MD) ?>" class="img-circle img-thumbnail profile-picture"/>

                        <div class="clearfix"></div>

                        <input type="file" style="display:none" id="picture-upload-input" name="picture"/>

                        <a href="#" class="btn btn-sm btn-purple picture-upload-link" data-loading-text="Uploading...">Change
                            Picture</a>
                        <div class="clearfix"></div>
                        <small>Picture must be smaller than 6mb</small>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="sr-only" for="lastname">Last Name</label>
                        <input type="text" class="form-control" id="lastname" placeholder="Last Name" name="lastname"
                               tabindex="2" value="<%= lastname %>">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="lastname">Mobile Phone</label>
                        <input type="text" class="form-control" id="mobile_phone" placeholder="Mobile Phone" name="mobile_phone"
                               tabindex="4" value="<%= mobile_phone %>">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="gender">Gender</label>
                        <select name="gender" class="form-control" id="gender" placeholder="Gender" tabindex="6">
                            <option value="">Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="timezone">Timezone</label>
                        <?= form_timezone('timezone', '', 'class="form-control" tabindex="8"') ?>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="about">About</label>
                        <textarea class="form-control" name="about" rows="10" placeholder="About"
                                  tabindex="6"><%= about %></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 submit-container">
                    <button class="submit btn btn-primary pull-right btn-lg" data-loading-text="Saving...">Save</button>
                    <div class="alert alert-success pull-right" style="display:none"></div>
                    <div class="alert alert-danger pull-right" style="display:none"></div>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-pane registration" id="mood">
        <? include(APPPATH.'/views/partials/registration/step2.php'); ?>
    </div>
    <div class="tab-pane registration" id="history">
        <? include(APPPATH.'/views/partials/registration/step3.php'); ?>
    </div>
    <div class="tab-pane registration" id="know">
        <? include(APPPATH.'/views/partials/registration/step4.php'); ?>
    </div>
    <div class="tab-pane registration" id="personality">
        <? include(APPPATH.'/views/partials/registration/step5.php'); ?>
    </div>
</div>