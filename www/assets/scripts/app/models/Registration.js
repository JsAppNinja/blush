'use strict';
app.Registration = app.BaseModel.extend({

    defaults: {
        step: 1,
        completed: 0,
        firstname: '',
        lastname: '',
        email: '',
        birthday: '',
        mobile_phone: '',
        gender: '',
        referral: '',
        referral_counselor: '',
        referral_other: '',
        address: '',
        state: '',
        zipcode: '',
        city: '',
        school_occupation: '',
        username: '',
        password: '',
        preferred_coach: '',
        preferred_coaching_time: '',
        coaching_qualifications: '',
        parent_email: '',
        parent_consent: 0,

        /* Step 3 */
        counselor_before_more: '',
        drugs_alcohol_more: '',
        sleeping_changes_more: '',
        medical_diagnosis_more: '',
        suicide_homicide_more: '',

        /* Step 4 */
        pop_culture: '',
        interest: '',
        dream: '',
        family: '',
        focus: ''
    },

    urlRoot: function() {
        if(this.user_uuid) {
            return app.rest_root + 'registrations/user/'+this.user_uuid;
        } else {
            return app.rest_root + 'registrations/registration';
        }
    }
});