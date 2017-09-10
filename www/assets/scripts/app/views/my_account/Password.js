'use strict';
app.PasswordView = app.BaseFormView.extend({
    template_name: 'my_account/password',

    events: {
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.password').addClass('active');

        return this;
    }

});