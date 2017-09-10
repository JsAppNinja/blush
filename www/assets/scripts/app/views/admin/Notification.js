'use strict';
app.admin.NotificationView = app.BaseFormView.extend({

    id: 'notification',

    template_name: 'admin/notification',

    events: {
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save",  /* BaseFormView.save() */
        "click .btn-test" : "test"
    },

    render: function () {
        var me = this;
        app.BaseFormView.prototype.render.call(this);
        setTimeout(function() {
            me.$el.find('#notification-body').show();
            tinymce.init({
                menubar: false,
                selector: 'textarea#notification-body',
                height:400,
                inline_styles : true
            });
        },500);

        return this;
    },

    cancel: function (event) {
        app.router.navigate('notifications', true);
        return false;
    },

    save : function(event, callback) {
        var attributes = {
            body: tinyMCE.activeEditor.getContent({format : 'raw'})
        };

        app.BaseFormView.prototype.save.call(this, event, this.model, callback, this.$el, attributes);
        return false;
    },

    test: function(event) {
        var me = this;
        this.save(event, function() {
            me.$el.find('.submit-container .alert').hide();
            $.ajax(app.rest_root + 'admin/notifications/test/', {
                type: 'POST',
                data: {
                    emails : me.$el.find('#test-emails').val()
                },
                dataType: 'json',
                success: function (response, textStatus, jqXHR) {
                    app.success_message(response.message, me.alert_container);
                    me.$el.find('#test_notification_modal').modal('hide');
                    //app.router.navigate('payouts', true);
                },
                error: function (jqXHR, textStatus, error) {
                    app.error_message($.parseJSON(jqXHR.responseText).message, me.alert_container);
                    me.$el.find('#test_notification_modal').modal('hide');
                    //submit_btn.button('reset');
                }
            });
        })
        return false;
    }
});