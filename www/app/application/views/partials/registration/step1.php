<form action="" method="post"  class="std-form">
    <div class="row">
        <div class="col-lg-12 title">
            <h3><strong>Make Us Blush:</strong> Give Us The Details</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="firstname">First Name</label>
                <input type="text" tabindex="1" class="form-control" id="firstname" placeholder="First Name" name="firstname" value="<%=firstname%>" data-rule-required="true">
            </div>
            <div class="form-group">
                <label class="sr-only" for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<%=email%>" data-rule-email="true" data-rule-required="true"
                       tabindex="3" data-rule-remote="<?=app_url('accounts/query_email')?>">
            </div>
            <div class="form-group date">
                <label class="sr-only" for="birthday">Birthday</label>
                <input type="text" tabindex="5" class="form-control datepicker" id="birthday" placeholder="Birthday" name="birthday" data-rule-date="true" data-rule-required="true" value="<%=birthday%>">
                <i class="glyphicons calendar"></i>
            </div>
            <!--
            <div class="form-group">
                <label class="sr-only" for="referral">Referral Source</label>
                <select name="referral" tabindex="7" class="form-control" id="referral" placeholder="Referral Source">
                    <option value="">Referral Source</option>
                    <option value="Google/Search Engines">Google/Search Engines</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Flyer">Flyer</option>
                    <option value="My Coach">Blush Coach</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            -->
            <div class="form-group">
                <label class="sr-only" for="referral">How did you find us?</label>
                <input type="text" class="form-control" tabindex="7"  id="referral" placeholder="How did you find us?" name="referral" value="<%=referral%>" data-rule-required="true">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="lastname">Last Name</label>
                <input type="text" tabindex="2" class="form-control" id="lastname" placeholder="Last Name" name="lastname" value="<%=lastname%>" data-rule-required="true">
            </div>
            <div class="form-group">
                <label class="sr-only" for="lastname">Mobile Phone</label>
                <input type="tel" tabindex="4" class="form-control" id="mobile_phone" placeholder="Mobile Phone" name="mobile_phone" value="<%=mobile_phone%>">
            </div>
            <div class="form-group">
                <label class="sr-only" for="gender">Gender</label>
                <select name="gender" tabindex="6" class="form-control" id="gender" placeholder="Gender" data-rule-required="true">
                    <option value="">Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label class="sr-only" for="school_occupation">Student and/or Occupation</label>
                <input type="text" tabindex="8" class="form-control" id="school_occupation" placeholder="Student and/or Occupation" name="school_occupation" value="<%=school_occupation%>">
            </div>
            <div class="form-group counselor" style="display:none">
                <label class="sr-only" for="counselor">Coach</label>
                <input type="text" class="form-control" id="referral_counselor" placeholder="Coach" name="referral_counselor" value="<%=referral_counselor%>">
            </div>
        </div>
    </div>
    <div class=" parent-email-container" style="display:none">

        <hr/>
        <div class="row">
            <div class="col-lg-12 title">
                <h4>Parent/Guardian Consent</h4>
                <p>Due to the fact that you are currently under 18 years of age, Blush requires that we get your parent or guardian's permission before creating your account.</p>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="parent_consent">
                        <input type="checkbox" value="1" id="parent_consent" name="parent_consent" <% if(parent_consent>0) { %>checked="checked" <% } %>/>
                        I am under the age of 18 and have the consent from my parent or legal guardian to participate in Blush Coaching.
                    </label>
                </div>
            </div>
        </div>
    </div>


    <hr/>
    <div class="row">
        <div class="col-lg-12 title">
            <h4>Location</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="city">City/Country</label>
                <input type="text"tabindex="9"  class="form-control" id="city" placeholder="City" name="city" data-rule-required="true" value="<%=city%>">
            </div>
            <!--
            <div class="form-group">
                <label class="sr-only" for="address">Address</label>
                <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="<%=address%>">
            </div>
            -->
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="state">State</label>
                <?= form_states('state', '', TRUE, 'class="form-control" tabindex="10" ')?>
            </div>

            <div class="form-group">
                <span class="checkbox">
                    <input type="checkbox" class="non-us"/> I'm not in the United States.
                </span>
            </div>
            <!--
            <div class="form-group">
                <label class="sr-only" for="zipcode">Zip Code</label>
                <input type="text" class="form-control" id="zipcode" placeholder="Zip Code" name="zipcode" value="<%=zipcode%>">
            </div>
            -->
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-lg-12 title">
            <h4>Account Details</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="username">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<%=username%>" data-rule-required="true" data-rule-minlength="4" data-rule-maxlength="20"
                       data-rule-remote="<?=app_url('accounts/query_username')?>"tabindex="11" >
            </div>
            <div class="form-group">
                <label class="sr-only" for="timezone">Timezone</label>
                <?= form_timezone('timezone', '', 'class="form-control" placeholder="Timezone" tabindex="13" ')?>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="sr-only" for="password">Password</label>
                <input type="password" class="form-control" id="password" tabindex="12" placeholder="Password" name="password" data-rule-required="true" data-rule-minlength="6">
            </div>
            <div class="form-group">
                <label class="sr-only" for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" tabindex="14" placeholder="Confirm Password" name="confirm_password" data-rule-equalTo="#password" data-rule-required="true">
            </div>
        </div>
    </div>

    <hr/>
    <div class="row">
        <div class="col-lg-12 title">
            <h4>Coaching Preferences</h4>
            </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <p>We cannot wait to match you with one of our coaches! If you have a strong preference for one of our Blush coaches, please list her name below. If not, you can leave it blank and we will do our magic! (We will try our very best to match you with her, schedule permitting!)</p>
                <label class="sr-only" for="preferred_coach">Preferred Coach</label>
                <select name="preferred_coach" tabindex="15" class="form-control" id="preferred_coach" placeholder="Preferred Coach">
                    <option value="">Preferred Coach</option>
                    <% _.each(counselors, function(counselor) { %>
                        <option value="<%=counselor.name%>"><%=counselor.name%></option>
                    <% }); %>
                </select>
            </div>
        </div>
        </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <p>When would you like to have your video sessions? Be as specific as possible (mornings, weekends, tuesdays, etc...</p>
                <label class="sr-only" for="preferred_coach">Preferred Coaching Time</label>
                <textarea class="form-control" id="preferred_coaching_time" tabindex="16" placeholder="Preferred Session Time"
                          name="preferred_coaching_time" value="<%=preferred_coaching_time%>" rows="6"/>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <p>Any specific experience/qualifications you would like your coach to have? (aka religious/spiritual counseling, health/fitness, LGTBQ, etc.)</p>
                <label class="sr-only" for="coaching_qualifications">Preferred Coaching Time</label>
                <textarea class="form-control" id="coaching_qualifications" tabindex="17" placeholder="Preferred Coaching Qualifications"
                          name="coaching_qualifications" value="<%=coaching_qualifications%>" rows="6"/>
            </div>
        </div>

        <div class="col-lg-6">
        </div>
    </div>


    <div class="row">

        <input type="hidden" name="step" value="1"/>

        <div class="col-lg-12 submit-container">
            <button class="btn btn-primary next pull-right btn-md" tabindex="17" data-loading-text="Saving..." >Next <i class="glyphicon glyphicon-chevron-right"></i></button>
            <div class="alert alert-success pull-right" style="display:none"></div>
            <div class="alert alert-danger pull-right" style="display:none"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</form>