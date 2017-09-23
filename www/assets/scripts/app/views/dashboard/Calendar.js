'use strict';
app.CalendarView = app.BaseView.extend({

    template_name: 'dashboard/calendar',

    // event_template: '<div data-uuid="<%=uuid%>" class="event">' +
    //                     '<div class="title"><%=title%></div>' +
    //                 '</div>', 

    event_template: '<span class="addtocalendar atc-base">' +
                        '<var class="atc_event">' +
                            '<var class="atc_date_start"><%= event.date %> <%= start_time %></var>' +
                            '<var class="atc_date_end"><%= event.date %> <%= event.end_time %></var>' +
                            '<var class="atc_timezone"><%= app.user.timezone %></var>' +
                            '<var class="atc_title">Video Session with <%= app.user.firstname %></var>' +
                            '<var class="atc_description">Video Session with <%= title %></var>' +
                            '<var class="atc_location">Video Session</var>' +
                            '<var class="atc_organizer"><%= app.user.counselor.firstname %> <%= app.user.counselor.lastname %></var>' +
                            '<var class="atc_organizer_email"><%= app.user.counselor.email %></var>' +
                        '</var>' +
                    '</span>',

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