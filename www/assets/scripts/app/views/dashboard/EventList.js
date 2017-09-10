'use strict';
app.EventListView = app.BaseListView.extend({
    id: 'events',

    template_name: 'dashboard/events',

    events: {
        'click button.cancel' : 'cancel_event'
    },

    cancel_event : function(event) {
        var me = this;
        $(event.currentTarget).closest('.event').find('.alert-danger').empty().hide();
        var submit_button = $(event.currentTarget);
        submit_button.button('loading');

        var uuid = $(event.currentTarget).attr('data-uuid');
        $.ajax(app.rest_root + 'events/cancel', {
            type: 'POST',
            data: {
                uuid: uuid
            },
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                submit_button.button('reset');
                $(event.currentTarget).closest('.event').remove();
                app.success_message(response.message, me.$el.find('.alert-container'));
                app.user.credits = response.data.user.credits;
                app.user.pending_credits = response.data.user.pending_credits;
                $('#sidebar .credits strong').text(app.user.credits - app.user.pending_credits);
            },
            error: function (jqXHR, textStats, error) {
                submit_button.button('reset');
                var message = $.parseJSON(jqXHR.responseText).message;
                var alert = $(event.currentTarget).closest('.event').find('.alert-danger');
                alert.html('<strong>Error: </strong> '+message).show();
                submit_btn.button('reset');
            }
        });
        return false;
    }
});