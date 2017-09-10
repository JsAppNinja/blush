'use strict';
app.admin.PricingView = app.BaseFormView.extend({

    id: 'pricing',

    template_name: 'admin/pricing',

    events: {
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        var me = this;
        app.BaseFormView.prototype.render.call(this);

        return this;
    },

    save: function (event) {
        var me = this;
        app.BaseFormView.prototype.save.call(this, event, this.model, function () {
            me.$el.find('input.money').each(function () {
                $(this).val(accounting.formatMoney($(this).val()));
            })
        });
        return false;
    }
});