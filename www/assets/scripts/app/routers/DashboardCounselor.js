'use strict';
app.DashboardCounselorRouter = app.BaseRouter.extend({
    container: '#dashboard-container',

    root_url: app.app_root + 'dashboard/',

    routes: {
        'calendar': 'calendar',
        'calendar/': 'calendar',

        'conversations': 'conversation',
        'conversations/': 'conversation',
        'conversation/:id': 'conversation',
        'conversation/:id/': 'conversation',

        'message': 'message',
        'message/': 'message',

        'customer': 'customer',
        'customer/': 'customer',
        'customer/:id': 'customer',
        'customer/:id/': 'customer',
        'customer/:id/:path': 'customer',
        'customer/:id/:path/': 'customer',

        'customers': 'customers',
        'customers/': 'customers',

        '*path': 'calendar'
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
                    me.dashboardView = new app.DashboardCounselorView({
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
     * Loads the calendar and the list of events
     */
    calendar: function () {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.calendarView) {
                me.calendarView.remove();
            }

            /* Create a new view */
            me.calendarView = new app.CalendarView();
            me.renderView(me.calendarView, undefined, function() {
                me.calendarView.build_calendar();
            });
        });


    },

    /**
     * Loads the list of customers
     */
    customers: function () {
        var me = this;

        me._dashboard(function() {
            /* Kill the old view */
            if (me.customerListView) {
                me.customerListView.remove();
            }
            me.customerListView = new app.CustomerListView();

            if (!me.customerListCollection) {
                me.customerListCollection = new app.CustomerListCollection([], {
                    view: me.customerListView
                });
            }
            me.customerListCollection.fetch({
                success: function (collection) {
                    me.renderCollectionView(me.customerListView, collection);
                }
            });
        });


    },

    customer: function (customerId, path) {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.customerView) {
                me.customerView.remove();
            }

            /* Create a new view */
            me.customerView = new app.CustomerView({
                path: path,
                model: new app.Customer({
                    uuid: customerId
                })
            });
            /* Fetch calls the render */
            me.customerView.model.fetch({
                success: function () {
                    me.renderView(me.customerView);
                }
            });
        });
    },

    conversations : function() {

    },

    /**
     * Loads the dashboard and the list of conversations.  The conversation uuid can be null if the user has not clicked on
     * a conversation
     */
    conversation: function (conversation_uuid) {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.conversationListView) {
                me.conversationListView.remove();
            }
            me.conversationListView = new app.ConversationListView({
                conversation_uuid : conversation_uuid
            });

            if (!me.conversationListCollection) {
                me.conversationListCollection = new app.ConversationListCollection([], {
                    view: me.conversationListView
                });
            }
            me.conversationListCollection.fetch({
                success: function (collection) {
                    me.renderCollectionView(me.conversationListView, collection);
                }
            });
        });

    },

    message: function () {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.messageView) {
                me.messageView.remove();
            }

            /* Create a new view */
            me.messageView = new app.MessageView({
                model: new app.Message()
            });
            me.messageView.cancel_url = app.router.root_url + 'conversations/';
            /* Fetch calls the render */
            me.messageView.model.fetch({
                success: function () {
                    me.renderView(me.messageView, undefined, function() {
                        /* Scroll down to the diary window if necessary */
                        var targetOffset = $('#message').offset().top;
                        $('html,body').animate({scrollTop: targetOffset}, 300);
                    });
                }
            });
        });
    }
});

if(app.activeRouter==='dashboardCounselor') {
    app.dashboardCounselorRouter = new app.DashboardCounselorRouter();
    app.router = app.dashboardCounselorRouter;
    $(document).ready(function() {
        app.dashboardCounselorRouter.start(app.app_root+'dashboard/');
    });
}