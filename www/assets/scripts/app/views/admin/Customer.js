'use strict';
app.admin.CustomerView = app.BaseFormView.extend({

    id: 'customer',

    template_name: 'admin/customer',

    events: {
        "click #delete_modal .btn-delete-confirm" : "delete_permanently",
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save",  /* BaseFormView.save() */
        "click #assign_counselor_modal .btn-primary": "assign_counselor"
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });
        return this;
    },

    cancel: function (event) {
        app.router.navigate('customers', true);
        return false;
    },

    assign_counselor : function(event) {
        var me = this;

        var counselor_id = $("#counselor_id").val();
        $("#assign_counselor_modal").modal('hide');

        $(".assign_counselor").button('loading');

        if(counselor_id) {
            $.post( app.rest_root+'admin/users/user_counselor', {
                uuid: this.model.id,
                counselor_id: counselor_id
            }, function(response) {
                me.model = new app.User(response);
                me.render();
            });
        }

        return false;
    },

    delete_permanently : function(event) {
        var me = this;
        $.post( app.rest_root+'admin/users/delete', {
            uuid: this.model.id
        }, function(response) {
            app.success_message(response.message, me.alert_container);
            app.router.navigate('customers', true);
            $('body').removeClass('modal-open');
        });
        return false;
    }
});