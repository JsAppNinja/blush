'use strict';
app.admin.TransactionView = app.BaseView.extend({

    id: 'transaction',

    template_name: 'admin/transaction',

    events: {
        "click .submit-container button.back": "back",
        "click .customer": "customer",
        "click .counselor": "counselor"
    },

    render: function () {
        app.BaseView.prototype.render.call(this);
        return this;
    },

    back: function (event) {
        app.router.navigate('transactions', true);
        return false;
    },

    customer: function (event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        app.router.navigate('customer/' + uuid, true);
        return false;
    },

    counselor: function (event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        app.router.navigate('counselor/' + uuid, true);
        return false;
    }
});