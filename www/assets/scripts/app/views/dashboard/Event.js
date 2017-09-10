'use strict';
app.EventView = app.BaseView.extend({
    id: 'event',
    className: 'event col-lg-12',
    template_name: 'dashboard/event',

    events: {
        "click .submit-container button.cancel": "cancel"
    },

    cancel: function (event) {
        app.router.navigate('events', true);
        return false;
    }
});