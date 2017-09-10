'use strict';
app.NoteListCollection = app.BaseListCollection.extend({
    model: app.Note,

    url: function() {
        if(this.uuid) {
            return app.rest_root + 'notes/user/'+this.uuid;
        } else {
            return app.rest_root + 'notes/';
        }
    }
});