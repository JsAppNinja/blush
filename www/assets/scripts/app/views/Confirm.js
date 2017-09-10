'use strict';
app.ConfirmView = Backbone.View.extend({
    id: 'confirm',

    template: function (data) {
        var template =  _.template('<div class="text"><p><%=text%></p></div>');
        return template(data);
    },

    initialize: function (options) {
        this.options = options;
    },

    render: function () {
        var attributes = this.options ? this.options : {};
        this.$el.html(this.template(attributes));

        return this;
    }
});