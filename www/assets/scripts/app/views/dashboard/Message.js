'use strict';
app.MessageView = app.BaseFormView.extend({
    id: 'message',
    template_name: 'dashboard/message',
    cancel_url :  app.get_router() ? app.get_router().root_url + 'messages/' : app.root_url + 'messages/',

    events: {
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        return this;
    },

    cancel: function (event) {
        app.router.navigate(this.cancel_url, true);
        return false;
    }
});