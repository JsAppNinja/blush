'use strict';
app.DiaryListCollection = app.BaseListCollection.extend({
    model: app.Diary,
    url: function() {
        if(this.uuid) {
            return app.rest_root + 'diaries/user/'+this.uuid;
        } else {
            return app.rest_root + 'diaries/';
        }
    }
});