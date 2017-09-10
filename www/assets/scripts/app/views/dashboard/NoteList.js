'use strict';
app.NoteListView = app.BaseListView.extend({
    id: 'notes',
    template_name: 'dashboard/notes',
    user_uuid : undefined,

    events: {
        'click .submit-container .btn-primary' : 'add_note'
    },

    render: function (collection) {
        app.BaseListView.prototype.render.call(this, collection);
        return this;
    },

    add_note : function(event) {
        event.stopPropagation();

        var note = new app.Note();
        note.urlRoot = note.urlRoot + '/' + this.options.user_uuid;
        this.save(event, note);
        return false;
    }
});