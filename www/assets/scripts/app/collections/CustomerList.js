'use strict';
app.CustomerListCollection = app.BaseListCollection.extend({
    model: app.Customer,
    url: app.rest_root + 'customers'
});