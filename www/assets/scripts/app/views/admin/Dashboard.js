'use strict';
app.admin.DashboardView = app.BaseView.extend({

    id: 'dashboard',

    template_name: 'admin/dashboard',

    events: {
        "click button.submit" : "update_config"
    },
    update_config : function(event) {
        var attributes = {};
        var me = this;
        this.$el.find('.config-value').each(function() {
            var value = $(this).val();
            var key = $(this).attr('name');

            /* Handle checkbox fields */
            if($(this).attr('type')==='checkbox' && !$(this).is(":checked")) {
                if($(this).val()==1) {
                    value = 0;
                }
            }

            attributes[key] = value;
        });
        $(event.currentTarget).button('loading');

        $.post(app.rest_root+'admin/system_config/config', {config: attributes}, function(response) {
            $(event.currentTarget).button('reset');
            app.success_message('Successfully updated the system configuration', me.$el.find('.alert-success'));
        }, 'json');
        return false;
    }


});