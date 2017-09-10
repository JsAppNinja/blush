'use strict';
app.CustomerListView = app.BaseListView.extend({
    id: 'customers',
    template_name: 'dashboard/customers',

    events: {
        'click .customer': 'customer'
    },

    render: function (collection) {
        app.BaseListView.prototype.render.call(this, collection);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.customers').addClass('active');

        return this;
    },

    customer: function (event) {
        var id = $(event.currentTarget).attr('data-id');
        app.router.navigate('customer/' + id + '/info', true);
    }
});