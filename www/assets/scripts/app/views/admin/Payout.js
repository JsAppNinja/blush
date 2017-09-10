'use strict';
app.admin.PayoutView = app.BaseView.extend({

    id: 'payout',

    template_name: 'admin/payout',

    events: {
        "click .submit-container button.back": "back",
        "click .submit-container button.pay": "pay"
    },

    initialize: function (options) {
        this.options = _.extend({
            remote: true,
            method: 'POST',
            perPage: 20,
            columns: [
                {
                    "title": "Transaction Type",
                    "mDataProp": "transaction_type"
                },
                {
                    "title": "Created",
                    "mDataProp": "created"
                },
                {
                    "title": "Customer",
                    "mDataProp": "customer"
                },
                {
                    "title": "Amount",
                    "mDataProp": "amount"
                }
            ],
            sorting: []
        }, options);
    },

    render: function () {
        this.model.set('columns',this.options.columns);
        app.BaseView.prototype.render.call(this);

        this.render_table();
        return this;
    },

    back: function (event) {
        app.router.navigate('payouts', true);
        return false;
    },

    render_table: function () {
        var me = this;

        this.table = this.$el.find('#datatable').dataTable({
            "sAjaxSource": app.rest_root + 'admin/payouts/payable_items/'+this.model.get('uuid'),
            "sServerMethod": this.options.method,
            "bServerSide": this.options.remote,
            "iDisplayLength": this.options.perPage,
            "aoColumns": this.options.columns,
            "aaSorting": this.options.sorting
        });
    }
});