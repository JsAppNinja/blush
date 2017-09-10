'use strict';
app.BaseStepView = app.BaseFormView.extend({
    events: {
        'click .submit-container .previous': 'previous',
        'click .submit-container .next': 'next',
        'change .yes-no-more input': 'toggle_more_text'
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        var me = this;
        this.$el.find('.choices input').each(function (input) {
            var name = $(this).attr('name');
            var value = $(this).attr('value');
            if (me.model.get(name) === value) {
                $(this).parent().button('toggle');
            }
        });
        return this;
    },

    previous: function (event) {
        var attributes = this.$el.find('form').serializeObject();
        var me = this;

        this.model.save(attributes, {
            success: function (model, response, options) {
                if (response && response.message) {
                    if (response.data && response.data.uuid) {
                        me.model.set('uuid', response.data.uuid);
                    }

                    /* Remove the password since we don't want to send it back up again */
                    me.model.unset('password');
                    me.model.unset('confirm_password');
                }
                app.router.navigate(me.previous_url, true);
            }

        });

        return false;
    },

    next: function (event) {

        if (this.$el.find('form').valid()) {

            var attributes = this.$el.find('form').serializeObject();
            var me = this;

            var submit_button = this.$el.find('.submit-container button.submit');
            submit_button.button('loading');

            this.model.save(attributes, {
                success: function (model, response, options) {
                    if (response && response.message) {
                        if (response.data && response.data.uuid) {
                            me.model.set('uuid', response.data.uuid);
                        }

                        /* Remove the password since we don't want to send it back up again */
                        me.model.unset('password');
                        me.model.unset('confirm_password');
                    }
                    //submit_button.button('reset');
                    app.router.navigate(me.next_url, true);
                },
                error: function (model, xhr, options) {
                    var alert = me.$el.find('.submit-container .alert-danger');
                    alert.html('<strong>Error: </strong> There was an error with your submission.  Please try again').show();
                    submit_button.button('reset');
                }

            });
        }

        return false;
    },

    toggle_more_text: function (event) {
        var value = $(event.currentTarget).val();
        var more_field = this.$el.find('div.' + $(event.currentTarget).attr('name'));
        if (value === 'Yes') {
            more_field.show();
        } else {
            more_field.hide();
        }
    }

});