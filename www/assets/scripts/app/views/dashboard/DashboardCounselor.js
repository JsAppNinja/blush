'use strict';
app.DashboardCounselorView = app.BaseView.extend({

    el: '#backbone-container',
    template_name: 'dashboard/dashboard',

    events: {
        "click #sidebar .nav .calendar": "calendar",
        "click #sidebar .nav .messages": "conversations",
        "click #sidebar .nav .customers": "customers",
        "click button.start-session" : "start_video_chat"
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        this.upcoming_event();

        return this;
    },

    calendar: function (event) {
        app.router.navigate('calendar', true);
        return false;
    },

    conversations: function (event) {
        app.router.navigate('conversations', true);
        return false;
    },

    customers: function (event) {
        app.router.navigate('customers', true);
        return false;
    },

    /* See if the user has an upcoming event */
    upcoming_event: function(event) {
        var me = this;
        app.fetch_upcoming_event(me);
        setTimeout(function() {
            me.upcoming_event();
        }, 60000);
    },

    start_video_chat : function(event) {
        var cache_buster = new Date().getTime();
        window.open(app.app_root+'chats/event/'+$(event.currentTarget).attr('data-id')+'/'+cache_buster, 'Blush Video Session', 'height=600,width=800,status=no,toolbar=yes,titlebar=no');
        return false;
    }

});