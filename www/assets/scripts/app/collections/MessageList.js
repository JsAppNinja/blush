'use strict';
app.MessageListCollection = app.BaseListCollection.extend({
    model: app.Message,

    url: function() {
        if(this.uuid) {
            return app.rest_root + 'messages/conversation/'+this.uuid;
        } else {
            return app.rest_root + 'messages/';
        }
    }
});