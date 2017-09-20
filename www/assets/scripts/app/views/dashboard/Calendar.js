'use strict';
app.CalendarView = app.BaseView.extend({

    template_name: 'dashboard/calendar',

    event_template: '<div data-uuid="<%=uuid%>" class="event">' +
                        '<a href="http://www.google.com/calendar/event?action=TEMPLATE&text=[event-title]&dates=20160627/20160629&details=[description]&location=[location]&trp=false&sprop=&sprop=name:" class="title"><%=title%></a>' +
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

//addtocalendar.com/atc/google?utz=-300&uln=en-US&vjs=1.5&e[0][date_start]=2017-09-25%2016%3A30%3A00&e[0][date_end]=2017-09-25%2017%3A00%3A00&e[0][timezone]=America%2FChicago&e[0][title]=Video%20Session%20with%20Kali&e[0][description]=Video%20Session%20with%20Kali&e[0][location]=Video%20Session&e[0][organizer]=Kali%20Rogers&e[0][organizer_email]=