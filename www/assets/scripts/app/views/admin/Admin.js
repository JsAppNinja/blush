'use strict';
app.admin.AdminView = app.BaseView.extend({

    el: '#page',
    template_name: 'admin/admin',

    events: {
        "click #sidebar .dashboard a": "dashboard",
        "click #sidebar .counselors a": "counselors",
        "click #sidebar .customers a": "customers",
        "click #sidebar .transactions a": "transactions",
        "click #sidebar .payouts a": "payouts",
        "click #sidebar .payables a": "payables",
        "click .page-title .action-container .btn": "btn_click"
    },

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        return this;
    },

    dashboard: function (event) {
        app.router.navigate('dashboard', true);
        return false;
    },

    counselors: function (event) {
        app.router.navigate('counselors', true);
        return false;
    },

    customers: function (event) {
        app.router.navigate('customers', true);
        return false;
    },

    transactions: function (event) {
        app.router.navigate('transactions', true);
        return false;
    },

    payouts: function (event) {
        app.router.navigate('payouts', true);
        return false;
    },

    payables: function (event) {
        app.router.navigate('payables', true);
        return false;
    },

    set_page: function (title, active_class, btn_text, btn_callback) {
        this.$el.find('.page-title h1').text(title);
        this.$el.find('#sidebar li.active').removeClass('active');
        this.$el.find('#sidebar li.' + active_class).addClass('active');
        /* Empty out the header actions */
        this.$el.find('.page-title .action-container').empty();

        if (btn_text) {
            this.$el.find('.page-title .action-container').append(
                '<button class="btn btn-primary btn-med"><i class="glyphicon glyphicon-plus"></i> ' + btn_text + '</button>');
            this.btn_callback = btn_callback;
        }
    },

    btn_click: function (event) {
        if (this.btn_callback) {
            this.btn_callback(event);
        }
    }
});