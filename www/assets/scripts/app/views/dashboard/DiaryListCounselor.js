'use strict';
app.DiaryListCounselorView = app.BaseListView.extend({
    id: 'diaries',

    template_name: 'dashboard/diaries-counselor',

    events: {
        'click .diary-counselor .title' : 'toggle_diary',
        'click .comments button.submit' : 'save_comments'
    },

    /**
     * Show the body of the diary to the counselor and if it is un-read, mark it as read
     * @param event
     */
    toggle_diary: function (event) {
        var body = $(event.currentTarget).parent().find('.body-comments');
        body.toggle();
        if(body.is(":visible")) {
            /* If the counselor is viewing this for the first time, mark it as read */
            var parent = $(event.currentTarget).closest('.diary-counselor');
            var isRead = parent.attr('data-read');
            if(parseInt(isRead)<1) {
                var diary = new app.Diary({uuid: parent.attr('data-uuid'), read: 1, draft: 0});
                diary.save();
                parent.attr('data-read', 1);
            }
        }
    },

    /**
     * Fired when the counselor clicks the save button on the comments form.  Updates the comments attached to the form.
     * @param event
     * @returns {boolean}
     */
    save_comments : function(event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        this.save(event, new app.Diary({uuid: uuid, draft: 0}), undefined, $(event.currentTarget).closest('.body-comments'));
        return false;
    }
});