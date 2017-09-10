'use strict';
app.AvailabilityCalendarView = app.BaseView.extend({

    template_name: 'my_account/includes/availability-calendar-calendar',
    event_template: '<div data-id="<%=id%>" class="availability <%=cls%>">' +
        '<div class="title"><%=title%></div>' +
        '<button class="calendar-remove"><i class="fa fa-times text-danger"></i></button>' +
        '</div>',

    events: {
        'click .fc-day .fc-day-number' : 'show_modal',
        'click .btn-save-availability-calendar' : 'save',
        'click .calendar-remove' : 'destroy',
        'change .is_all_day' : 'change_is_all_day'
    },

    initialize: function (options) {
        app.BaseView.prototype.initialize.call(this, options);
        this.on('save_complete', this.after_save);
    },

    render: function () {
        app.BaseView.prototype.render.call(this);

        return this;
    },

    destroy: function(event) {
        var id = $(event.currentTarget).closest('.availability').data('id');
        this.model = new app.AvailabilityCalendar({id: id});
        app.BaseView.prototype.destroy.call(this, event);
    },

    change_is_all_day: function(event) {
        var is_checked = $(event.currentTarget).prop('checked');
        if(is_checked) {
            this.$el.find('.form-group-date').hide();
            this.$el.find('.is_all_day_val').val(1);
            this.$el.find('.form-group-date input').data('rule-required', false);
        } else {
            this.$el.find('.form-group-date').show();
            this.$el.find('.is_all_day_val').val('');
            this.$el.find('.form-group-date input').data('rule-required', true);
        }
    },

    after_save: function(event) {
        $('#availability-calendar-modal').modal('hide');
        this.$el.find("#availability-calendar").fullCalendar('refetchEvents');
    },

    show_modal: function(event) {
        var date = $(event.currentTarget).closest('.fc-day').data('date');
        this.model = new app.AvailabilityCalendar();

        this.$el.find('#availability-calendar-modal input,#availability-calendar-modal select').val('');
        this.$el.find('#availability-calendar-modal input').prop('checked', '');
        this.$el.find('.form-group-date').show();
        this.$el.find('.is_all_day_val').val('');
        this.$el.find('#availability-calendar-modal input[name="date"]').val(date);
        this.$el.find('#availability-calendar-modal').modal('show');
    },

    build_calendar: function () {
        var me = this;

        this.$el.find("#availability-calendar").fullCalendar({
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            contentHeight: 1000,
            events: app.rest_root + 'users/availability_calendar/',
            eventRender : function(ev, element, view) {
                $(element).attr('data-uuid', ev.uuid).html(_.template(me.event_template, ev));
            },
            eventAfterAllRender: function(view) {
                $('.fc-day .fc-day-number').each(function() {
                    if($(this).find('.fa').length<1) {
                        $(this).append('<i class="fa fa-plus-circle"></i>');
                    }
                });
            }
        });
    }
});