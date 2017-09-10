'use strict';
app.MyAccountRouter = app.BaseRouter.extend({
    container: '#my-account-container',
    root_url: app.app_root + 'my_account/',

    routes: {
        'profile': 'profile',
        'profile/': 'profile',

        'notifications': 'notifications',
        'notifications/': 'notifications',

        'password': 'password',
        'password/': 'password',

        'payment': 'payment',
        'payment/': 'payment',

        'account_type': 'account_type',
        'account_type/': 'account_type',

        'availability': 'availability',
        'availability/': 'availability',
        '*path': 'profile'
    },

    /**
     * Private function that is called to initiate the dashboard as a whole
     * @private
     */
    _my_account: function (callback) {
        /* Load the templates up into the template cache and then kick off the main view */
        if (!this.myAccountView) {
            this.myAccountView = new app.MyAccountView();
            this.renderViewComplex(this.myAccountView, callback);
        } else {
            callback();
        }
    },

    _get_user: function (callback) {
        if (!this.user) {
            this.user = new app.User();
            this.user.fetch({
                success: callback
            });
        } else {
            callback();
        }
    },

    profile: function () {
        var me = this;
        /* Kill the old view */
        if (me.profileView) {
            me.profileView.remove();
        }

        this._get_user(function () {

            me.registration = new app.Registration();
            me.registration.user_uuid = app.user.uuid;

            me._my_account(function() {
                me.registration.fetch({
                    success: function() {
                        me.profileView = new app.ProfileView({
                            model: me.user,
                            registration : me.registration.toJSON()
                        });
                        /* Store the actual model on the profile view so we can use it when saving */
                        me.profileView.registration = me.registration;
                        me.renderView(me.profileView);
                    }
                });
            });
        });
    },

    notifications: function () {
        var me = this;
        /* Kill the old view */
        if (me.notificationsView) {
            me.notificationsView.remove();
        }
        this._get_user(function () {
            me._my_account(function() {
                me.notificationsView = new app.NotificationsView({
                    model: me.user
                });
                me.renderView(me.notificationsView);
            });
        });
    },

    password: function () {
        var me = this;
        /* Kill the old view */
        if (me.passwordView) {
            me.passwordView.remove();
        }
        this._get_user(function () {
            me._my_account(function() {
                me.passwordView = new app.PasswordView({
                    model: me.user
                });
                me.renderView(me.passwordView);
            });
        });
    },

    account_type: function () {
        var me = this;
        /* Kill the old view */
        if (me.accountTypeView) {
            me.accountTypeView.remove();
        }
        this._get_user(function () {
            me._my_account(function() {

                me.pricing = new app.Pricing();
                me.pricing.fetch({
                    success: function () {
                        me.accountTypeView = new app.AccountTypeView({
                            model: me.user,
                            pricing: me.pricing
                        });
                        me.renderView(me.accountTypeView);
                    }
                });
            });
        });
    },

    availability: function () {
        var me = this;
        /* Kill the old view */
        if (me.availabilityView) {
            me.availabilityView.remove();
        }
        this._get_user(function () {
            me._my_account(function() {

                me.availability = new app.Availability();
                me.availability.fetch({
                    success: function () {
                        me.availabilityView = new app.AvailabilityView({
                            model: me.user,
                            availability: me.availability
                        });
                        me.renderView(me.availabilityView);
                    }
                });
            });
        });
    },

    payment: function () {
        var me = this;
        /* Kill the old view */
        if (me.paymentView) {
            me.paymentView.remove();

            /* Force reload of the template */
            app.template_cache.remove('my_account/payment');
        }

        this._get_user(function () {
            me._my_account(function() {
                me.paymentView = new app.PaymentView({
                    model: me.user
                });
                me.renderView(me.paymentView);
            });
        });
    }
});

if(app.activeRouter==='myAccount') {
    app.myAccountRouter = new app.MyAccountRouter();
    app.router = app.myAccountRouter;
    $(document).ready(function() {
        app.myAccountRouter.start(app.app_root+'my_account/');
    });
}