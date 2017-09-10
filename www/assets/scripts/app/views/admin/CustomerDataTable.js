'use strict';
app.admin.CustomerDataTableView = app.DataTableView.extend({

    template_name: 'admin/datatable-customers',
    counselor_id: 0,

    events: {
        'click .dataTable tr td': 'edit',
        'change #counselor_id' : 'set_counselor_id'
    },

    set_counselor_id : function(event) {
        this.counselor_id = $(event.currentTarget).val();
        this.table.fnDraw();
    },

    get_filter_params: function (aoData) {
        aoData.push({ "name":"counselor_id", "value": this.counselor_id });
    }
});