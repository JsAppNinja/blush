'use strict';
app.ConversationListCollection = app.BaseListCollection.extend({
    model: app.Conversation,
    url: app.rest_root + 'conversations'
});