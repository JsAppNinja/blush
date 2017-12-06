'use strict';
app.PaymentView = app.BaseFormView.extend({
    template_name: 'my_account/payment',

    events: {
        "click .submit-container button.submit": "save",  /* PaymentView.save() */
        "click .card-fields-toggle" : "toggle_card_fields"
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.payment').addClass('active');

        return this;
    },

    save: function (event) {
        if (this.$el.find('form').valid()) {

            var submit_button = this.$el.find('.submit-container button.submit');
            submit_button.button('loading');
            this.$el.find('.submit-container .alert').hide();

            if($('.card-number').length>0) {
                /* Allow them to save without a card so they can update their location */
                var number = $('.card-number').val();
                if(number) {
                    this.save_card(submit_button);
                } else {
                    this.save_success(null, 'credit');
                }
            } else {
                /* Allow them to save without a card so they can update their location */
                var number = $('.account-number').val();
                if(number) {
                    this.save_checking(submit_button);
                } else {
                    this.save_success(null, 'checking');
                }
            }
        }
        return false;
    },

    save_card : function(submit_button) {
        var me = this;
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val(),
            name: $(".cardholder-name").val()
        }, function (status, response) {
            me.save_success(response, 'credit');

            // if (response.error) {
            //     var alert = me.$el.find('.submit-container .alert-danger');
            //     alert.show().text(response.error.message);
            //     submit_button.button('reset');
            // } else {
            //     me.save_success(response, 'credit');
            // }
        });
    },

    save_checking : function(submit_button) {
        var me = this;
        Stripe.bankAccount.createToken({
            country:'US',
            routingNumber:$('.routing-number').val(),
            accountNumber:$('.account-number').val()
        }, function (status, response) {

            me.save_success(response, 'checking');
            //
            // if (response.error) {
            //     var alert = me.$el.find('.submit-container .alert-danger');
            //     alert.show().text(response.error.message);
            //     submit_button.button('reset');
            // } else {
            //     me.save_success(response, 'checking');
            // }
        });
    },

    /**
     * Fired when the stripe call completes to save the form
     * @param response
     * @param account_type
     */
    save_success : function(response, account_type) {
        /* Call the save method on baseForm */
        var form$ = this.$el.find("#payment-form");
        if(response) {
            var token = response['id'];
            form$.append("<input type='hidden' name='stripe_token' value='" + token + "' />");
        }
        this.model.set('account_type', account_type);

        app.BaseFormView.prototype.save.call(this);
        this.model.set('stripe_token', '');
    },

    toggle_card_fields : function(event) {
        this.$el.find('.card-fields').toggle();
        return false;
    }

});