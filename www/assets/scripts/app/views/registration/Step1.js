'use strict';
app.Step1View = app.BaseStepView.extend({
    VAL_MY_COUNSELOR : 'My Coach',
    VAL_OTHER : 'Other',

    events: {
        'click .submit-container .previous': 'previous',
        'click .submit-container .next': 'next',
        'change .yes-no-more input': 'toggle_more_text',
        'change #referral' : 'referral_source',
        'change #birthday' : 'validate_birthday',
        'click .non-us' : 'toggle_state'
    },

    template_name: 'registration/step1',
    next_url: 'plan',

    render: function () {
        if(!this.model.get('timezone')) {
            this.model.set('timezone', app.get_timezone());
        }
        app.BaseStepView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });

        return this;
    },

    referral_source : function(event) {
        this.$el.find('div.counselor').hide().find('input').removeAttr('data-rule-required');
        this.$el.find('div.other').hide().find('input').removeAttr('data-rule-required');

        if($(event.currentTarget).val()==this.VAL_MY_COUNSELOR) {
            this.$el.find('div.counselor').show().find('input').attr('data-rule-required', 'true');
        } else if($(event.currentTarget).val()==this.VAL_OTHER) {
            this.$el.find('div.other').show().find('input').attr('data-rule-required', 'true');
        }
    },

    toggle_state: function(event) {
        var checked = $(event.currentTarget).prop('checked');
        console.log(checked);
        if(checked) {
            this.$el.find('#state').hide();
        } else {
            this.$el.find('#state').show();
        }
    },

    /* If the visitor is < 13, they cannot register.  If they are < 18, they must provide a parent's email address */
    validate_birthday : function(event) {
        var container = $('.alert-container');
        app.clear_message(container);
        var age = app.calculate_age($(event.currentTarget).val());
        if(age<13) {
            this.$el.find('.btn-primary').attr('disabled','disabled');
            app.error_message('You must be over the age of 13 in order to use Blush.', container, 30000);
            return;
        } else if(age<18) {
            this.$el.find('.btn-primary').removeAttr('disabled');
            this.$el.find('.parent-email-container').show();
            $("#parent_consent").attr('data-rule-required', true);
            return;
        } else {
            this.$el.find('.btn-primary').removeAttr('disabled');
            this.$el.find('.parent-email-container').hide();
        }


        this.$el.find('.submit').removeAttr('disabled');
        $("#parent_email").removeAttr('data-rule-required');
        return false;
    }
});