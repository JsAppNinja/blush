'use strict';
app.DiaryListCustomerView = app.BaseListView.extend({
    id: 'diaries',

    template_name: 'dashboard/diaries',

    events: {
        'click .diary': 'open_diary'
    },

    open_diary: function (event) {
        var id = $(event.currentTarget).attr('data-id');
        app.router.navigate('diary/' + id, true);
    }
});