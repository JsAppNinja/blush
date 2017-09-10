'use strict';
app.Dashboard = app.BaseModel.extend({
    defaults: {
        counselors: 0,
        customers: 0,
        requests: 0,
        transactions: 0,

        last_30_customers: 0,
        last_30_counselors: 0,
        last_30_requests: 0,
        last_30_transactions: 0,
        last_30_money: 0
    },

    urlRoot: app.rest_root + 'admin/dashboard'
});