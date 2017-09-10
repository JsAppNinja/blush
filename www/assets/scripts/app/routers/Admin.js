'use strict';
app.AdminRouter = app.BaseRouter.extend({
    root_url: app.admin_root,

    routes: {

        'counselors': 'counselors',
        'counselors/': 'counselors',

        'counselor': 'counselor',
        'counselor/:id': 'counselor',

        'customers': 'customers',
        'customers/': 'customers',

        'customer': 'customer',
        'customer/:id': 'customer',

        'requests': 'requests',
        'requests/': 'requests',

        'transactions': 'transactions',
        'transactions/': 'transactions',

        'transaction': 'transaction',
        'transaction/:id': 'transaction',

        'payouts': 'payouts',
        'payouts/': 'payouts',

        'payout': 'payout',
        'payout/:id': 'payout',

        'payables': 'payables',
        'payables/': 'payables',

        'payable': 'payable',
        'payable/:id': 'payable',

        'notifications': 'notifications',
        'notifications/': 'notifications',

        'notification': 'notification',
        'notification/:id': 'notification',

        'pricing': 'pricing',

        '*path': 'dashboard'
    },

    /**
     * Private function that is called to initiate the dashboard as a whole
     * @private
     */
    _admin: function (page_title, active_class, btn_text, btn_callback) {
        /* Load the templates up into the template cache and then kick off the main view */
        if (!this.adminView) {
            this.adminView = new app.admin.AdminView({ });
            this.adminView.render();
        }
        this.adminView.set_page(page_title, active_class, btn_text, btn_callback);
    },

    dashboard: function () {
        var me = this;

        app.template_cache.load(['admin/admin', 'admin/dashboard'], function () {
            me._admin('Dashboard', 'dashboard');

            /* Kill the old view */
            if (me.dashboardView) {
                me.dashboardView.remove();
            }

            /* Create a new view */
            me.dashboardView = new app.admin.DashboardView({
                model: new app.Dashboard({})
            });
            /* Fetch calls the render */
            me.dashboardView.model.fetch({
                success: function () {
                    me.renderView(me.dashboardView);
                }
            });
        });
    },

    /**
     * Loads the list of counselors
     */
    counselors: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Coaches', 'counselors', 'Add Coach', function () {
                app.adminRouter.navigate('counselor', true);
            });

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/users/counselors',
                edit_url: 'counselor/',
                cls: 'counselors',
                columns: [
                    {
                        title: 'Last Name',
                        mDataProp: 'lastname'
                    },
                    {
                        title: 'First Name',
                        mDataProp: 'firstname'
                    },
                    {
                        title: 'Username',
                        mDataProp: 'username'
                    },
                    {
                        title: 'Created',
                        mDataProp: 'created'
                    },
                    {
                        title: 'Last Login',
                        mDataProp: 'last_login'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    counselor: function (counselorId) {
        var me = this;

        app.template_cache.load(['admin/admin', 'admin/counselor'], function () {
            if (counselorId) {
                me._admin('Edit Coach', 'counselors');
            } else {
                me._admin('Add Coach', 'counselors');
            }

            /* Kill the old view */
            if (me.counselorView) {
                me.counselorView.remove();
            }

            /* Create a new view */
            me.counselorView = new app.admin.CounselorView({
                model: new app.User({
                    urlRoot: app.rest_root + 'admin/users/user',
                    uuid: counselorId,
                    user_type_id: app.user_type_counselor
                })
            });
            /* Fetch calls the render */
            me.counselorView.model.fetch({
                success: function () {
                    me.renderView(me.counselorView);
                }
            });
        });
    },

    /**
     * Loads the list of customers
     */
    customers: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Customers', 'customers', 'Add Customer', function () {
                app.adminRouter.navigate('customer', true);
            });

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.admin.CustomerDataTableView({
                url: app.rest_root + 'admin/users/customers',
                edit_url: 'customer/',
                cls: 'customers',
                columns: [
                    {
                        title: 'Last Name',
                        mDataProp: 'lastname'
                    },
                    {
                        title: 'First Name',
                        mDataProp: 'firstname'
                    },
                    {
                        title: 'Username',
                        mDataProp: 'username'
                    },
                    {
                        title: 'Created',
                        mDataProp: 'created'
                    },
                    {
                        title: 'Last Login',
                        mDataProp: 'last_login'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    customer: function (customerId) {
        var me = this;

        app.template_cache.load(['admin/admin', 'admin/customer'], function () {
            if (customerId) {
                me._admin('Edit Customer', 'customers');
            } else {
                me._admin('Add Customer', 'customers');
            }

            /* Kill the old view */
            if (me.customerView) {
                me.customerView.remove();
            }

            /* Create a new view */
            me.customerView = new app.admin.CustomerView({
                model: new app.User({
                    urlRoot: app.rest_root + 'admin/users/user',
                    uuid: customerId,
                    user_type_id: app.user_type_customer
                })
            });
            /* Fetch calls the render */
            me.customerView.model.fetch({
                success: function () {
                    me.renderView(me.customerView);
                }
            });
        });
    },

    /**
     * Loads the list of counselors
     */
    requests: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Requests', 'requests');
        });
    },

    /**
     * Loads the list of transactions
     */
    transactions: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Transactions', 'transactions');

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/transactions',
                edit_url: 'transaction/',
                cls: 'transactions',
                sorting: [
                    [ 1, 'desc' ]
                ],
                columns: [
                    {
                        title: 'Transaction Number',
                        mDataProp: 'transaction_nbr'
                    },
                    {
                        title: 'Created',
                        mDataProp: 'created'
                    },
                    {
                        title: 'Customer',
                        mDataProp: 'customer'
                    },
                    {
                        title: 'Coach',
                        mDataProp: 'counselor'
                    },
                    {
                        title: 'Amount',
                        mDataProp: 'amount'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    transaction: function (transactionId) {
        var me = this;

        if (!transactionId) {
            app.adminRouter.navigate('transactions', true);
            return;
        }

        app.template_cache.load(['admin/admin', 'admin/transaction'], function () {
            me._admin('View Transaction', 'transactions');

            /* Kill the old view */
            if (me.transactionView) {
                me.transactionView.remove();
            }

            /* Create a new view */
            me.transactionView = new app.admin.TransactionView({
                model: new app.Transaction({
                    urlRoot: app.rest_root + 'admin/transactions/transaction',
                    uuid: transactionId
                })
            });
            /* Fetch calls the render */
            me.transactionView.model.fetch({
                success: function () {
                    me.renderView(me.transactionView);
                }
            });
        });
    },

    /**
     * Loads the list of payouts
     */
    payouts: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Past Payouts', 'payouts');

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/payouts',
                edit_url: 'payout/',
                cls: 'payouts',
                sorting: [
                    [ 1, 'desc' ]
                ],
                columns: [
                    {
                        title: 'Date',
                        mDataProp: 'created'
                    },
                    {
                        title: 'Last Name',
                        mDataProp: 'lastname'
                    },
                    {
                        title: 'First Name',
                        mDataProp: 'firstname'
                    },
                    {
                        title: 'Transaction Nbr',
                        mDataProp: 'stripe_transfer_id'
                    },
                    {
                        title: 'Amount',
                        mDataProp: 'amount'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    payout: function (payoutId) {
        var me = this;

        if (!payoutId) {
            app.adminRouter.navigate('payouts', true);
            return;
        }

        app.template_cache.load(['admin/admin', 'admin/payout'], function () {
            me._admin('Pay Coach', 'payouts');

            /* Kill the old view */
            if (me.payoutView) {
                me.payoutView.remove();
            }

            /* Create a new view */
            me.payoutView = new app.admin.PayoutView({
                model: new app.Payout({
                    urlRoot: app.rest_root + 'admin/payouts/payout',
                    uuid: payoutId
                })
            });
            /* Fetch calls the render */
            me.payoutView.model.fetch({
                success: function () {
                    me.renderView(me.payoutView);
                }
            });
        });
    },

    /**
     * Loads the list of counselors who need to be paid
     */
    payables: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Coaches to be Paid', 'payables');

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/payables',
                edit_url: 'payable/',
                cls: 'payables',
                sorting: [
                    [ 1, 'desc' ]
                ],
                columns: [
                    {
                        title: 'Last Name',
                        mDataProp: 'lastname'
                    },
                    {
                        title: 'First Name',
                        mDataProp: 'firstname'
                    },
                    {
                        title: 'Username',
                        mDataProp: 'username'
                    },
                    {
                        title: 'Amount Due',
                        mDataProp: 'amount'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    payable: function (payableId) {
        var me = this;

        if (!payableId) {
            app.adminRouter.navigate('payables', true);
            return;
        }

        app.template_cache.load(['admin/admin', 'admin/payable'], function () {
            me._admin('Pay Coach', 'payables');

            /* Kill the old view */
            if (me.payableView) {
                me.payableView.remove();
            }

            /* Create a new view */
            me.payableView = new app.admin.PayableView({
                model: new app.Payable({
                    urlRoot: app.rest_root + 'admin/payables/payable',
                    uuid: payableId
                })
            });
            /* Fetch calls the render */
            me.payableView.model.fetch({
                success: function () {
                    me.renderView(me.payableView);
                }
            });
        });
    },

    /**
     * Loads the list of counselors who need to be paid
     */
    notifications: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Automated Email Notification Templates', 'notifications');

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/notifications',
                edit_url: 'notification/',
                cls: 'notifications',
                columns: [
                    {
                        title: 'Template',
                        mDataProp: 'name',
                        sClass : 'name'
                    },
                    {
                        title: 'Description',
                        mDataProp: 'description',
                        sClass : 'description'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    notification: function (notificationId) {
        var me = this;

        if (!notificationId) {
            app.adminRouter.navigate('notifications', true);
            return;
        }

        app.template_cache.load(['admin/admin', 'admin/notification'], function () {
            me._admin('Edit Notification Template', 'notifications');

            /* Kill the old view */
            if (me.notificationView) {
                me.notificationView.remove();
            }

            /* Create a new view */
            me.notificationView = new app.admin.NotificationView({
                model: new app.Notification({
                    urlRoot: app.rest_root + 'admin/notifications/notification',
                    uuid: notificationId
                })
            });
            /* Fetch calls the render */
            me.notificationView.model.fetch({
                success: function () {
                    me.renderView(me.notificationView);
                }
            });
        });
    },

    pricing: function () {
        var me = this;

        app.template_cache.load(['admin/admin', 'admin/pricing'], function () {
            me._admin('Edit Pricing', 'pricing');

            /* Kill the old view */
            if (me.pricingView) {
                me.pricingView.remove();
            }

            /* Create a new view */
            me.pricingView = new app.admin.PricingView({
                model: new app.Pricing({
                    urlRoot: app.rest_root + 'admin/plans/pricing'
                })
            });
            /* Fetch calls the render */
            me.pricingView.model.fetch({
                success: function () {
                    me.renderView(me.pricingView);
                }
            });
        });
    }
});

if(app.activeRouter==='admin') {
    app.adminRouter = new app.AdminRouter();
    app.router = app.adminRouter;
    $(document).ready(function() {
        app.adminRouter.start(app.app_root+'admin/');
    });
}