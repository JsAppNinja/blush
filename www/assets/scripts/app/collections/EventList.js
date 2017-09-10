'use strict';
app.EventListCollection = app.BaseListCollection.extend({
    model: app.Event,

    url: function() {
        if(this.uuid) {
            return app.rest_root + 'events/user/'+this.uuid;
        } else {
            return app.rest_root + 'events/';
        }
    }
});