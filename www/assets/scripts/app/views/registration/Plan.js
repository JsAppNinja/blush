'use strict';
app.PlanView = app.BaseStepView.extend({
    id: 'plans',

    TYPE_ALACARTE: 'alacarte',
    TYPE_SUBSCRIPTION: 'subscription',

    chosen_plan_id: undefined,
    coupon_code: undefined,
    chosen_price: 0,
    chosen_plan_name: undefined,

    events: {
        'click .submit-container .previous': 'previous',
        'click .submit-container .submit': 'submit',
        'click a.choose': 'choose',
        'click a.cancel': 'cancel',
        'click button.cancel': 'cancel',
        'click .card-submit-container button.submit': 'submit_payment',
        'click .coupon-code-button': 'check_coupon_code',
        'click .coupon-code-reset-button': 'coupon_code_remove'
    },

    template_name: 'registration/plan',
    previous_url: '',

    initialize: function (options) {
        app.BaseStepView.prototype.initialize.call(this, options);

        /** Set the pricing value to be the actual json of the object **/
        this.options.pricing = this.options.pricing.toJSON();
    },

    render: function () {

        app.BaseStepView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });

        return this;
    },

    cancel: function (event) {
        $('.item-container').removeClass('inactive').removeClass('active');
        $(".card-fields").hide();

        $('.alacartes').show();
        $('.plans').show();
        return false;
    },

    choose: function (event) {

        this.chosen_plan_id = $(event.currentTarget).attr('data-id');
        this.chosen_price = $(event.currentTarget).closest('.plan-item').find('.price span').data('original');
        this.chosen_plan_name = $(event.currentTarget).closest('.plan-item').find('h4').text();
        $(event.currentTarget).closest('.item-container').addClass('active');
        $('.item-container:not(.active)').addClass('inactive');

        $(".card-fields").show();

        /* Decorate the plans that were chosen/unchosen */
        var type = $(event.currentTarget).attr('data-type');
        if (type === this.TYPE_ALACARTE) {
            this.choose_alacarte(event);
        } else {
            this.choose_subscription(event);
        }
        return false;
    },

    choose_subscription: function (event) {
        $('.alacartes').hide();
        $('.coupon-code-container').show();
    },

    choose_alacarte: function (event) {
        $('.plans').hide();
        $('.coupon-code-container').show();
    },

    check_coupon_code: function (event) {
        var me = this;
        var coupon_input = $(event.currentTarget).closest('.input-group').find('input');
        var value = coupon_input.val();
        var price_field = $(".plan-" + this.chosen_plan_id + " .price span");
        $(".coupon-code-alerts .alert").hide();

        me.coupon_code = undefined;

        if (value) {
            $.ajax(app.rest_root + 'plans/coupon_code', {
                dataType: 'json',
                data: {
                    code: value,
                    plan_id: this.chosen_plan_id
                },
                success: function(response) {
                    price_field.text(response.data.price);
                    $(".coupon-code-alerts .alert-success").show();
                    $(event.currentTarget).hide();
                    me.$el.find('.coupon-code-reset-button').show();
                    me.coupon_code = value;

                },
                error : function() {
                    $(".coupon-code-alerts .alert-danger").show();
                    coupon_input.val('');
                    me.coupon_code = '';

                }
            });
        } else {
            price_field.val(price_field.attr('data-original'));
        }
    },

    coupon_code_remove : function(event) {
        this.$el.find('.coupon-code-button').show();
        $(".coupon-code-alerts .alert").hide();
        $(event.currentTarget).hide();
        this.coupon_code = '';
        var price_field = $(".plan-" + this.chosen_plan_id + " .price span");
        price_field.text(price_field.data('original'));
        $(event.currentTarget).closest('.input-group').find('input').val('');
        return false;
    },

    record_conversion : function(event, response) {
        if(window._fbq && window._fbq.push) {
            window._fbq.push(['track', '6030582505998', {'value':this.chosen_price,'currency':'USD'}]);
        }

        console.log(ga);
        ga('ecommerce:addTransaction', {
            'id': response.user.uuid,
            'affiliation': 'Blush',
            'revenue': this.chosen_price,
            'shipping': 0,
            'tax': 0
        });

        ga('ecommerce:addItem', {
            'id': response.user.uuid,
            'name': this.chosen_plan_name,
            'sku': '',
            'category': '',
            'price': this.chosen_price,
            'quantity': 1
        });

        ga('ecommerce:send');
    },

    submit_payment: function (event) {
        var me = this;
        var form = $(event.currentTarget).closest('form');

        var submit_button = $(event.currentTarget);

        if (form.valid()) {
            submit_button.button('loading');
            Stripe.createToken({
                number: $(form).find('.card-number').val(),
                cvc: $(form).find('.card-cvc').val(),
                exp_month: $(form).find('.card-expiry-month').val(),
                exp_year: $(form).find('.card-expiry-year').val(),
                name: $(form).find(".cardholder-name").val()
            }, function (status, response) {

                if (response.error) {
                    var alert = form.find('.alert-danger');
                    alert.show().text(response.error.message);
                    submit_button.button('reset');
                } else {

                    var attributes = {
                        chosen_plan_id: me.chosen_plan_id,
                        code: me.coupon_code,
                        token: response['id'],
                        completed: 1
                    }

                    me.model.save(attributes, {
                        success: function (model, response) {
                            submit_button.button('reset');

                            var data = response.data;

                            if (data && data.inactive > 0) {
                                window.location = app.app_root + 'login/'
                            }
                            me.record_conversion(event, response.data);
                            window.location = app.app_root + 'dashboard/';
                        },
                        error: function (model, xhr) {
                            var alert = me.$el.find('.submit-container .alert-danger');

                            var errorMsg = '';
                            if(xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else {
                                errorMsg = '<strong>Error: </strong> There was an error with your submission.  Please try again';
                            }

                            alert.html(errorMsg).show();
                            submit_button.button('reset');
                        }
                    });
                }
            });
        }
        return false;
    }
});