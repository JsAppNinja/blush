'use strict';
app.DataTableView = app.BaseView.extend({

    template_name: 'admin/datatable',

    table: undefined,

    events: {
        'click .dataTable tr td': 'edit'
    },

    initialize: function (options) {
        options = options || {};
        this.options = _.extend({
            remote: true,
            method: 'POST',
            perPage: 20,
            columns: [],
            sorting: []
        }, options);
    },

    render: function () {
        this.$el.html(this.template(this.options));

        this.render_table();
        /* Build the datatable */

        return this;
    },

    get_filter_params: function () {
        return;
    },

    render_table: function () {
        var me = this;

        this.table = this.$el.find('#datatable').dataTable({
            sAjaxSource: this.options.url,
            bServerSide: this.options.remote,
            sServerMethod: this.options.method,
            iDisplayLength: this.options.perPage,
            aoColumns: this.options.columns,
            aaSorting: this.options.sorting,
            fnServerParams: function(aodata) {
                me.get_filter_params(aodata);
            }
        });
    },

    edit: function (event) {
        var id = $(event.currentTarget).closest('tr').attr('id');
        app.router.navigate(this.options.edit_url + id, true);
        return false;
    }
});