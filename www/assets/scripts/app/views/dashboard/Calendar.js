'use strict';
app.CalendarView = app.BaseView.extend({

    template_name: 'dashboard/calendar',

    event_template: '<div data-uuid="<%=uuid%>" class="event">' +
                        '<a href="http://addtocalendar.com/atc/google?utz=-500&uln=en-US&vjs=1.5&e[0][date_start]=<%= day %> <%= month %> 2017 <%= start_time %>&e[0][date_end]=<%=day %> <%=month%> 2017 <%=end_time%>&e[0][timezone]=<%=app.user.timezone%>&e[0][title]=<%=title%>&e[0][description]=<%=text%>&e[0][location]=Online&e[0][organizer]=<%= app.user.firstname %> <%= app.user.lastname %>[0][organizer_email]=" class="title"><%=title%></a>' +
                       ' <span class="addtocalendar atc-base"><var class="atc_event"><var class="atc_date_start"><%= event.date %> <%= event.start_time %></var><var class="atc_date_end"><%= event.date %> <%= event.end_time %></var><var class="atc_timezone"><%= app.user.timezone %></var><var class="atc_title">Video Session with <%= app.user.counselor.firstname %></var><var class="atc_description">Video Session with <%= app.user.counselor.firstname %></var><var class="atc_location">Video Session</var><var class="atc_organizer"><%= app.user.counselor.firstname %> <%= app.user.counselor.lastname %></var><var class="atc_organizer_email"><%= app.user.counselor.email %></var></var></span>'
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