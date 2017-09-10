'use strict';
app.Diary = app.BaseModel.extend({
    urlRoot: app.rest_root + 'diaries/diary',
    defaults: {
        draft: 1
    }
});