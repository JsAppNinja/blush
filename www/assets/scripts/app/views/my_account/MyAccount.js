'use strict';
app.MyAccountView = app.BaseView.extend({

    el: '#backbone-container',
    template_name: 'my_account/my_account',

    events: {
        "click .side-nav li a": "route"
    },

    render: function () {
        app.BaseView.prototype.render.call(this);
        return this;
    },

    route : function(event) {
        app.router.navigate($(event.currentTarget).attr('class'),true);
        return false;
    }
});