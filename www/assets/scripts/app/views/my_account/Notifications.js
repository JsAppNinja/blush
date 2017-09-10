'use strict';
app.NotificationsView = app.BaseFormView.extend({
    template_name: 'my_account/notifications',

    events: {
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.notifications').addClass('active');

        return this;
    }

});