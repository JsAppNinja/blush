'use strict';
app.VideoAddView = app.BaseFormView.extend({
    id: 'video_add',
    template_name: 'dashboard/videoadd',
    template_availability_name: 'dashboard/includes/videoadd-availability',


    events: {
        "change #session_date" : "update_timeslots",
        "click #video-add-submit": "save"  /* BaseFormView.save() */
    },

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);

        /** Set the pricing value to be the actual json of the object **/
        this.options.availability = this.options.availability.toJSON();
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });
        return this;
    },

    /**
     * Validates that the user cannot schedule a video session within 24 hours of current time
     * and that they aren't picking a time that their coach isn't available.
     */
    validate : function() {
        var minDate = app.calculate_24hours();

        var date = this.$el.find('#session_date').val();
        var time = this.$el.find('#session_time').val();

        var dateTime = app.date_from_input(date, time);
        if(minDate.getTime() > dateTime.getTime() && app.data.prevent_schedule_24hour) {
            app.error_message('Video sessions cannot be schedule less than 24 hours in advance.', this.$el.find('.alert-holder'));
            return false;
        }
        return true;
    },

    /**
     * Pull any custom timeslots from the calendar for the specified date
     * @param event
     */
    update_timeslots: function(event) {
        var me = this,
            val = $(event.currentTarget).val();
        $.ajax({
            url: app.rest_root+'events/timeslots',
            method: 'POST',
            data: {
                session_date: val
            },
            success: function(response) {
                var html = _.template(app.template_cache.get(me.template_availability_name), {
                    model: response,
                    session_date: val
                });
                me.$el.find('.additional-availability').html(html);
            }
        });
    },

    save: function (event) {
        if (this.$el.find('form').valid() && this.validate()) {
            var submit_button = $(event.currentTarget);
            submit_button.button('loading');

            var attributes = this.$el.find('form').serializeObject();
            var me = this;
            $.ajax({
                url: app.rest_root+'events/video_add',
                method: 'POST',
                data: attributes,
                success: function(response) {
                    submit_button.button('reset');
                    app.router.navigate('events', true);
                    app.success_message('Your video session has been scheduled successfully', $("#dashboard-alerts"));
                    app.user.credits = response.data.user.credits;
                    app.user.pending_credits = response.data.user.pending_credits;
                    $('#sidebar .credits strong').text(app.user.credits - app.user.pending_credits);
                },
                error: function(xhr, status, error) {
                    submit_button.button('reset');
                    var response = $.parseJSON(xhr.responseText);
                    if(response.message) {
                        app.error_message(response.message, me.$el.find('.alert-holder'))
                    }
                }
            });
        }
        return false;
    }
});