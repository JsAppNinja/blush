'use strict';
app.MessageListView = app.BaseListView.extend({
    id: 'messages',

    template_name: 'dashboard/messages',

    events: {
        'click .new-message': 'message',
        'click .submit-container .submit': 'reply'
    },

    message: function (event) {
        /* If this is a regular user and they don't have a coach yet, don't let them do anything */

        if((app.user.user_type_id == app.user_type_customer) && !app.user.counselor.uuid) {
            app.error_message(app.msg_error.no_counselor, this.alert_container);
        }  else {
            app.router.navigate('message', true);
        }
        return false;
    },

    reply: function (event) {
        /* Create a new message, send it, and fetch the list */
        var me = this;
        var message = new app.Message();

        var attributes = this.$el.find('form').serializeObject();
        attributes.conversation_uuid = this.options.conversation_uuid;

        var submit_button = this.$el.find('.submit-container button.submit');
        submit_button.button('loading');

        this.$el.find('.submit-container .alert').hide();

        message.save(attributes, {
            success: function (model, response, options) {
                if (response && response.message) {
                    var alert = me.$el.find('.submit-container .alert-success');
                    alert.html('<strong>Success: </strong> ' + response.message).show();
                    alert.delay(3000).fadeOut();
                    me.collection.fetch({
                        success: function (collection) {
                            me.render(collection);

                            /* Scroll down to the reply field */
                            var targetOffset = me.$el.find('.reply').offset().top;
                            $('html,body').animate({scrollTop: targetOffset}, 300);
                        }
                    });

                }
                submit_button.button('reset');
            },
            error: function (model, xhr, options) {
                var alert = me.$el.find('.submit-container .alert-danger');
                alert.html('<strong>Error: </strong> There was an error with your submission.  Please try again').show();
                submit_button.button('reset');
            }

        });
        return false;
    }
});