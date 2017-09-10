'use strict';
app.CreditsView = app.BaseView.extend({
    events: {
        'click .input-group-addon': 'toggle_count',
        'click button.submit': 'submit'
    },

    template_name: 'dashboard/credits',

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);
        this.bind('ok', this.confirm);
    },

    render: function () {
        app.BaseView.prototype.render.call(this);
        return this;
    },

    _get_diary_amount: function () {
        return Math.max(parseInt(this.$el.find('.diary-count input').val()), 0);
    },

    _get_counseling_amount: function () {
        return Math.max(parseInt(this.$el.find('.counseling-count input').val()), 0);
    },

    _get_total: function () {
        var diary_amount = this._get_diary_amount() * app.data.price_diary;
        var counseling_amount = this._get_counseling_amount() * app.data.price_counseling;

        return Math.max(diary_amount + counseling_amount, 0);
    },

    toggle_count: function (event) {
        this.$el.find('.no-selection-error:visible').hide();
        var input = $(event.currentTarget).siblings('input')
        if ($(event.currentTarget).hasClass('plus')) {
            input.val(parseInt(input.val()) + 1);
        } else {
            if (parseInt(input.val()) > 0) {
                input.val(parseInt(input.val()) - 1);
            }
        }

        this.$el.find('.header .total').text('Total: ' + accounting.formatMoney(this._get_total()));
    },

    confirm: function (modal) {
        this.modal = modal;
        if (this._get_total() <= 0) {
            this.$el.find('.no-selection-error').show();
            return false;
        }

        this.$el.find('#add-credits-form').hide();
        this.$el.find('#add-credits-confirm').show();
        modal.$el.find('.modal-footer').hide();
    },

    submit: function () {
        var me = this;
        var submit_btn = this.$el.find('button.submit');
        submit_btn.button('loading');

        $.ajax(app.rest_root + 'users/purchase', {
            type: 'POST',
            data: {
                diary_cnt: this._get_diary_amount(),
                counseling_cnt: this._get_counseling_amount()
            },
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                if (me.options.dashboard) {
                    me.options.dashboard.trigger('addCredits', response.data.credits);
                }

                if (me.modal)
                    me.modal.close();
            },
            error: function (jqXHR, textStats, error) {
                var alert = me.$el.find('.alert-danger');
                alert.html('<strong>Error: </strong> There was an error with your submission.  Please try again').show();
                submit_btn.button('reset');
            }
        });

    }
});