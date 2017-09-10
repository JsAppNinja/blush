'use strict';
app.admin.PayableView = app.BaseView.extend({

    id: 'payable',

    template_name: 'admin/payable',

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
        app.router.navigate('payables', true);
        return false;
    },

    pay: function(event) {
        var submit_btn = this.$el.find('button.pay');
        submit_btn.button('loading');

        var me = this;
        $.ajax(app.rest_root + 'admin/payables/pay/'+this.model.get('uuid'), {
            type: 'POST',
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                app.success_message(response.message, me.alert_container);
                app.router.navigate('payouts', true);
            },
            error: function (jqXHR, textStatus, error) {
                app.error_message($.parseJSON(jqXHR.responseText).message, me.alert_container);
                submit_btn.button('reset');
            }
        });
    },

    render_table: function () {
        var me = this;

        this.table = this.$el.find('#datatable').dataTable({
            "sAjaxSource": app.rest_root + 'admin/payables/payable_items/'+this.model.get('uuid'),
            "sServerMethod": this.options.method,
            "bServerSide": this.options.remote,
            "iDisplayLength": this.options.perPage,
            "aoColumns": this.options.columns,
            "aaSorting": this.options.sorting
        });
    }
});