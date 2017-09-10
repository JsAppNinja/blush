'use strict';
app.AvailabilityView = app.BaseFormView.extend({
    template_name: 'my_account/availability',

    events: {
        "click .submit-container button.add": "add", /* BaseFormView.save() */
        "click .remove": "remove"
    },

    add_template: '<tr data-id="<%=id%>">' +
        '<td><%=day_of_week%></td>' +
        '<td><%=pretty_start_time%></td>' +
        '<td><%=pretty_end_time%></td>' +
        '<td><button class="btn btn-xs pull-right btn-danger remove">Delete</button></td>' +
        '</tr>',

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);

        /** Set the pricing value to be the actual json of the object **/
        this.options.availability = this.options.availability.toJSON();
    },

    render: function () {
        var me = this;
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.availability').addClass('active');
        /* Create a new view of the availiability calendar*/

        setTimeout(function () {

            me.calendarView = new app.AvailabilityCalendarView({
                model: new app.AvailabilityCalendar()
            });
            app.get_router().renderView(me.calendarView, '#availability-calendar-container', function () {
                me.calendarView.build_calendar();
            });
        }, 500);

        setTimeout(function() {
            me.$el.find('#calendar').removeClass('active');
        },1000);


        return this;
    },

    remove: function (event) {
        var me = this;
        if (event) {
            var row = $(event.currentTarget).closest('tr');
            var id = row.attr('data-id');
            var data = {
                action: 'remove',
                id: id
            }

            $.ajax(app.rest_root + 'users/availability', {
                dataType: 'json',
                data: data,
                type: 'POST',
                success: function (response) {
                    app.success_message('Your availability day has been successfully removed.', me.$el.find('.alert-container'));
                    row.remove();
                },
                error: function () {
                    me.$el.find('.alert-danger').show().html('<p>There was an error while saving your entry, please try again.</p>');
                }
            });
        }
    },

    add: function (event) {
        var me = this;

        this.$el.find('.alert-danger').hide().empty();
        if (this.$el.find('form').valid()) {
            var data = {
                action: 'add',
                day: this.$el.find('#day').val(),
                start_time: this.$el.find('#start_time').val(),
                end_time: this.$el.find('#end_time').val()
            }

            if (data.start_time >= data.end_time) {
                this.$el.find('.alert-danger').show().html('<p>Start Time should be before End Time.</p>');
            } else {
                $.ajax(app.rest_root + 'users/availability', {
                    dataType: 'json',
                    data: data,
                    type: 'POST',
                    success: function (response) {
                        app.success_message('Your availability day has been successfully added.', me.$el.find('.alert-container'));
                        me.$el.find('table').append(_.template(me.add_template, response));
                    },
                    error: function () {
                        me.$el.find('.alert-danger').show().html('<p>There was an error while saving your entry, please try again.</p>');
                    }
                });
            }
        }

        return false;
    }

});