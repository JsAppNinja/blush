'use strict';
app.CustomerView = app.BaseFormView.extend({
    id: 'customer',
    template_name: 'dashboard/customer',

    noteListCollection: undefined,
    noteListView: undefined,

    diaryListCollection: undefined,
    diaryListView: undefined,

    events: {
        'click .dropper' : 'open_menu',
        'click .dropper-menu li' : 'menu_choice'
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.customers').addClass('active');

        if(this.options.path==='diaries') {
            this.renderDiaryList();
        } else if(this.options.path==='notes') {
            this.renderNotesList();
        } else {
            this.renderInfo();
        }
        return this;
    },

    open_menu : function() {
        this.$el.find('.dropper-menu').show();
        return false;
    },

    menu_choice : function(event) {
        this.$el.find('.dropper-menu li').removeClass('active');
        if($(event.currentTarget).hasClass('notes')) {
            this.renderNotesList();
            app.router.navigate('customer/' + this.model.get('uuid') + '/notes');
        } else if($(event.currentTarget).hasClass('diary')) {
            this.renderDiaryList();
            app.router.navigate('customer/' + this.model.get('uuid') + '/diaries');
        } else if($(event.currentTarget).hasClass('info')) {
            this.renderInfo();
            app.router.navigate('customer/' + this.model.get('uuid') + '/info');
        }
        this.$el.find('.dropper-menu').hide();
    },

    renderNotesList: function () {

        this.$el.find('.dropper .icon').hide();
        this.$el.find('.dropper-menu li.notes').addClass('active');
        this.$el.find('.dropper .icon-notes').css('display','block');

        var me = this;
        if (!this.noteListCollection) {
            this.noteListCollection = new app.NoteListCollection();
        }

        this.noteListCollection.uuid = this.model.get('uuid');
        this.noteListCollection.fetch({
            success: function (collection, response, options) {
                /* Kill the old view */
                if (me.noteListView) {
                    me.noteListView.remove();
                }
                me.noteListView = new app.NoteListView({
                    user_uuid : me.model.get('uuid')
                });

                app.router.renderCollectionView(me.noteListView, collection, '#item-list');
            }
        });
    },

    renderDiaryList: function () {

        this.$el.find('.dropper .icon').hide();
        this.$el.find('.dropper-menu li.diary').addClass('active');
        this.$el.find('.dropper .icon-diary').css('display','block');

        var me = this;
        if (!this.diaryListCollection) {
            this.diaryListCollection = new app.DiaryListCollection();
        }

        this.diaryListCollection.uuid = this.model.get('uuid');
        this.diaryListCollection.fetch({
            success: function (collection, response, options) {
                /* Kill the old view */
                if (me.diaryListView) {
                    me.diaryListView.remove();
                }
                me.diaryListView = new app.DiaryListCounselorView({
                    user_uuid : me.model.get('uuid')
                });

                app.router.renderCollectionView(me.diaryListView, collection, '#item-list');
            }
        });
    },

    renderInfo: function () {

        this.$el.find('.dropper .icon').hide();
        this.$el.find('.dropper-menu li.info').addClass('active');
        this.$el.find('.dropper .icon-info').css('display','block');

        var me = this;
        if (me.infoView) {
            me.infoView.remove();
        }
        me.infoView = new app.InfoView({
            model : me.model
        });

        app.router.renderView(me.infoView, '#item-list', function() {
        });

    }
});