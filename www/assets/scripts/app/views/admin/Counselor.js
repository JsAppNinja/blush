'use strict';
app.admin.CounselorView = app.BaseFormView.extend({

    id: 'counselor',

    template_name: 'admin/counselor',

    events: {
        "click #delete_modal .btn-delete-confirm" : "delete_permanently",
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        var me = this;
        /* Set the user_type_id to be counselor for when we are adding counselors */
        this.model.set('user_type_id', app.user_type_counselor);
        app.BaseFormView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });

        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.admin.CustomerDataTableView({
                url: app.rest_root + 'admin/users/customers/'+me.model.get('id'),
                edit_url: 'customer/',
                cls: 'customers',
                columns: [
                    {
                        "title": "Last Name",
                        "mDataProp": "lastname"
                    },
                    {
                        "title": "First Name",
                        "mDataProp": "firstname"
                    },
                    {
                        "title": "Username",
                        "mDataProp": "username"
                    },
                    {
                        "title": "Created",
                        "mDataProp": "created"
                    },
                    {
                        "title": "Last Login",
                        "mDataProp": "last_login"
                    }
                ]
            });
            /* Fetch calls the render */
            app.router.renderView(me.dataTableView, me.$el.find('#counselor-customers'));
        });

        return this;
    },

    cancel: function (event) {
        app.router.navigate('counselors', true);
        return false;
    },

    delete_permanently : function(event) {
        var me = this;
        $.post( app.rest_root+'admin/users/delete', {
            uuid: this.model.id
        }, function(response) {
            app.success_message(response.message, me.alert_container);
            app.router.navigate('counselors', true);
            $('body').removeClass('modal-open');
        });
        return false;
    }
});