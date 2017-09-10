'use strict';
app.ConversationListView = app.BaseListView.extend({
    id: 'conversations',

    messageListCollection : undefined,
    messageListView : undefined,

    template_name: 'dashboard/conversations',

    events: {
        'click .conversation' : 'conversation',
        'change #conversation-selector' : 'select_conversation'
    },

    render: function (collection) {
        app.BaseListView.prototype.render.call(this, collection);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.messages').addClass('active');

        /* When this is rendered, the conversation uuid is probably not set so we will just pull the first one
        off of the collection
         */
        if(!this.getConversationUuid()) {
            var firstConversation = _.first(collection.models);
            if(firstConversation) {
                this.setConversationUuid(firstConversation.get('uuid'));
            }
        }

        this.renderMessageList(this.getConversationUuid());

        this.$el.find('.conversation:first').addClass('active');

        return this;
    },

    conversation: function(event) {
        this.$el.find('.conversation').removeClass('active');
        this.setConversationUuid($(event.currentTarget).attr('data-id'));
        this.renderMessageList(this.getConversationUuid());

        $(event.currentTarget).addClass('active');
        return false;
    },

    select_conversation: function(event) {
        this.setConversationUuid($(event.currentTarget).val());
        this.renderMessageList(this.getConversationUuid());
        return false;
    },

    setConversationUuid : function(uuid) {
        this.options.conversation_uuid = uuid;
    },

    getConversationUuid : function() {
        return this.options.conversation_uuid;
    },

    renderMessageList: function(uuid) {
        var me = this;
        if(!this.messageListCollection) {
            this.messageListCollection = new app.MessageListCollection();
        }

        this.messageListCollection.uuid = uuid;
        this.messageListCollection.fetch({
            success : function(collection, response, options) {
                /* Kill the old view */
                if (me.messageListView) {
                    me.messageListView.remove();
                }
                me.messageListView = new app.MessageListView({
                    conversation_uuid : me.getConversationUuid()
                });

                app.router.renderCollectionView(me.messageListView, collection, '#message-list');
            }
        });
    }
});