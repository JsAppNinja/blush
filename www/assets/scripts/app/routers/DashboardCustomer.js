'use strict';
app.DashboardCustomerRouter = app.BaseRouter.extend({
    container: '#dashboard-container',

    root_url: app.app_root + 'dashboard/',

    routes: {
        'events': 'events',
        'events/': 'events',

        'messages': 'messages',
        'messages/': 'messages',

        'message': 'message',
        'message/': 'message',

        'diary': 'diary',
        'diary/': 'diary',
        'diary/:id': 'diary',
        'diary/:id/': 'diary',

        'event/:id': 'event',
        'event/:id/': 'event',

        'add-video': 'add_video',
        'add-video/': 'add_video',

        'diaries': 'diaries',
        'diaries/': 'diaries',

        '*path': 'events'
    },

    /**
     * Private function that is called to initiate the dashboard as a whole
     * @private
     */
    _dashboard: function (callback) {
        var me = this;
        /* Load the templates up into the template cache and then kick off the main view */
        if (!this.dashboardView) {
            this.user = new app.User();
            this.user.fetch({
                success: function() {
                    me.dashboardView = new app.DashboardCustomerView({
                        model : me.user
                    });
                    me.renderViewComplex(me.dashboardView, callback);
                }
            });
        } else {
            callback();
        }
    },
    /**
     * Loads the dashboard and the list of events
     */
    events: function () {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.eventListView) {
                me.eventListView.remove();
            }
            me.eventListView = new app.EventListView();

            if (!me.eventListCollection) {
                me.eventListCollection = new app.EventListCollection([], {
                    view: me.eventListView
                });
            }
            me.eventListCollection.fetch({
                success: function (collection) {
                    me.renderCollectionView(me.eventListView, collection);
                }
            });
        });
    },

    event: function (eventId) {
        var me = this;

        me._dashboard(function () {
            /* Kill the old view */
            if (me.eventView) {
                me.eventView.remove();
            }

            /* Create a new view */
            me.eventView = new app.EventView({
                model: new app.Event({
                    uuid: eventId
                })
            });
            /* Fetch calls the render */
            me.eventView.model.fetch({
                success: function () {
                    me.renderView(me.eventView, undefined, function() {
                        /* Scroll down to the diary window if necessary */
                        var targetOffset = $('#event').offset().top;
                        $('html,body').animate({scrollTop: targetOffset}, 300);
                    });
                }
            });
        });
    },

    add_video: function() {
        var me = this;

        me._dashboard(function () {
            if((parseInt(app.user.credits) - parseInt(app.user.pending_credits))>=app.data.credits_counseling) {
                /* Kill the old view */
                if (me.videoAddView) {
                    me.videoAddView.remove();
                }

                /* Create a new view */

                me.availability = new app.Availability();
                me.availability.fetch({
                    success: function () {
                        me.videoAddView = new app.VideoAddView({
                            availability: me.availability
                        });
                        me.renderView(me.videoAddView, undefined, function() {
                            /* Scroll down to the diary window if necessary */
                            var targetOffset = $('#video_add').offset().top;
                            $('html,body').animate({scrollTop: targetOffset}, 300);
                        });

                    }
                });
            } else {
                app.error_message(app.msg_error.credits_invalid_video, me.alert_container);
            }
        });
    },

    diary: function (diaryId) {
        var me = this;

        me._dashboard(function () {
            if(!app.user.counselor.uuid) {
                app.error_message(app.msg_error.no_counselor, me.alert_container);
            } else if(diaryId || (parseInt(app.user.credits) - parseInt(app.user.pending_credits))>=app.data.credits_diary) {
                /* Kill the old view */
                if (me.diaryView) {
                    me.diaryView.remove();
                }

                /* Create a new view */
                me.diaryView = new app.DiaryView({
                    model: new app.Diary({
                        uuid: diaryId
                    })
                });
                /* Fetch calls the render */
                me.diaryView.model.fetch({
                    success: function () {
                        me.renderView(me.diaryView, undefined, function() {
                            /* Scroll down to the diary window if necessary */
                            var targetOffset = $('#diary').offset().top;
                            $('html,body').animate({scrollTop: targetOffset}, 300);
                        });
                    }
                });
            } else {
                app.error_message(app.msg_error.credits_invalid_diary, me.alert_container);
            }
        });
    },

    /**
     * Loads the dashboard and the list of diaries
     */
    diaries: function () {
        var me = this;

        me._dashboard(function () {

            /* Kill the old view */
            if (me.diaryListView) {
                me.diaryListView.remove();
            }
            me.diaryListView = new app.DiaryListCustomerView();

            if (!me.diaryListCollection) {
                me.diaryListCollection = new app.DiaryListCollection([], {
                    view: me.diaryListView
                });
            }
            me.diaryListCollection.fetch({
                success: function (collection) {
                    me.renderCollectionView(me.diaryListView, collection);
                }
            });
        });
    },

    /**
     * Loads the dashboard and the list of diaries
     */
    messages: function () {
        var me = this;

        me._dashboard(function () {

            /* Get the conversation between this user and their coach */
            $.get(app.rest_root+'conversations/conversation', {}, function(data) {

                var conversation_uuid = 0;
                if(data && data.uuid) {
                    conversation_uuid = data.uuid;
                }

                /* Kill the old view */
                if (me.messageListView) {
                    me.messageListView.remove();
                }
                me.messageListView = new app.MessageListView({
                    conversation_uuid : conversation_uuid
                });

                if (!me.messageListCollection) {
                    me.messageListCollection = new app.MessageListCollection([], {
                        view: me.messageListView
                    });
                }
                me.messageListCollection.fetch({
                    success: function (collection) {


                        me.renderCollectionView(me.messageListView, collection);
                    }
                });
            });

        });
    },

    message: function () {
        var me = this;
        me._dashboard(function () {
            if(!app.user.counselor.uuid) {
                app.error_message(app.msg_error.no_counselor, me.alert_container);
            } else {
                /* Kill the old view */
                if (me.messageView) {
                    me.messageView.remove();
                }

                /* Create a new view */
                me.messageView = new app.MessageView({
                    model: new app.Message()
                });
                /* Fetch calls the render */
                me.messageView.model.fetch({
                    success: function () {
                        me.renderView(me.messageView, undefined, function() {

                        });

                        /* Scroll down to the diary window if necessary */
                        var targetOffset = $('#message').offset().top;
                        $('html,body').animate({scrollTop: targetOffset}, 300);
                    }
                });
            }
        });

    }
});

if(app.activeRouter==='dashboardCustomer') {
    app.dashboardCustomerRouter = new app.DashboardCustomerRouter();
    app.router = app.dashboardCustomerRouter;
    $(document).ready(function() {
        app.dashboardCustomerRouter.start(app.app_root+'dashboard/');
    });
}