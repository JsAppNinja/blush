'use strict';
app.DashboardCustomerView = app.BaseView.extend({

    el: '#backbone-container',
    template_name: 'dashboard/dashboard',

    events: {
        "click .user-meta .messages a": "messages",
        "click .user-meta .diaries a": "diaries",
        "click #btn-add-diary": "diary",
        "click #btn-add-video": "add_video",
        "click #btn-add-credits": "add_credits",
        "click .fc-event" : "event",
        "click button.start-session" : "start_video_chat"
    },

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);
        this.bind('addCredits', this.add_credits_complete);
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        this.calendar();

        this.upcoming_event();

        this.welcome();

        return this;
    },

    messages: function (event) {
        app.router.navigate('messages', true);
        return false;
    },

    event: function(event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        app.router.navigate('event/'+uuid, true);
        return false;
    },

    diary: function (event) {
        if(parseInt(app.user.credits)<app.data.credits_diary) {
            app.error_message(app.msg_error.credits_invalid_diary, this.alert_container);
        } else if(!app.user.counselor.uuid) {
            app.error_message(app.msg_error.no_counselor, this.alert_container);
        } else {
            app.router.navigate('diary', true);
        }
        return false;
    },

    add_video: function (event) {
        if(parseInt(app.user.credits)<app.data.credits_counseling) {
            app.error_message(app.msg_error.credits_invalid_video, this.alert_container);
        } else if(!app.user.counselor.uuid) {
            app.error_message(app.msg_error.no_counselor, this.alert_container);
        } else {
            app.router.navigate('add-video', true);
        }
        return false;
    },

    diaries: function (event) {
        app.router.navigate('diaries', true);
        return false;
    },

    add_credits: function (event) {
        var me = this;
        app.template_cache.load(['dashboard/credits'], function () {
            /* Kill the old view */
            if (me.modalView) {
                me.modalView.remove();
            }
            /* Create a new view */
            me.creditsView = new app.CreditsView({
                dashboard: me,
                model: me.model
            });
            me.modalView = new Backbone.BootstrapModal({
                content: me.creditsView,
                modalClass: 'add-credits',
                allowOk: me.model.get('stripe_customer_id'),
                okText: 'SUBMIT',
                okCloses: false
            }).open();
        });
        return false;
    },

    welcome : function () {
        var me = this;
        if(!app.user.previous_login) {
            $.get(app.rest_root+'users/welcome', {}, function(data) {
                if(data) {
                    me.modalView = new Backbone.BootstrapModal({
                        content: data,
                        title: 'Welcome!',
                        modalClass: 'welcome',
                        allowOk: true,
                        allowCancel: false
                    }).open();
                }
            });
        }
    },

    /**
     * The user has completed purchasing their credits
     * @param total_credits - the total number of credits for a user
     */
    add_credits_complete: function (total_credits) {
        app.success_message(app.msg_success.credits_purchase, this.alert_container);
        this.$el.find('div.credits strong').text(total_credits);
    },

    /* Initialize the calendar in the sidebar */
    calendar: function () {
        $("#sidebar-calendar").fullCalendar({
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            events: app.rest_root + 'events/',
            eventRender : function(ev, element, view) {
                $(element).attr('data-uuid', ev.uuid).html('<div class="fc-day-number">'+ev.day+'</div>');
            }
        })
    },

    /* See if the user has an upcoming event */
    upcoming_event: function() {
        app.fetch_upcoming_event(this);
    },

    start_video_chat : function(event) {
        var cache_buster = new Date().getTime();
        window.open(app.app_root+'chats/event/'+$(event.currentTarget).attr('data-id')+'/'+cache_buster, 'Blush Video Session', 'height=600,width=800,status=no,toolbar=yes,titlebar=no');
        return false;
    }

});