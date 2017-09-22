'use strict';
app.CalendarView = app.BaseView.extend({

    template_name: 'dashboard/calendar',

    event_template: '<div data-uuid="<%=uuid%>" class="event">' +
                        '<div class="title"><%=title%></div>' +
                    '</div>', 

    events: {
        'click .diary': 'open_diary'
    },

    initialize: function (options) {
        app.BaseView.prototype.initialize.call(this, options);
    },

    render: function () {
        app.BaseView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.calendar').addClass('active');

        return this;
    },

    build_calendar: function () {
        var me = this;

        this.$el.find("#main-calendar").fullCalendar({
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            events: app.rest_root + 'events/',
            eventRender : function(ev, element, view) {
                $(element).attr('data-uuid', ev.uuid).html(_.template(me.event_template, ev));
            }
        });
    }
});