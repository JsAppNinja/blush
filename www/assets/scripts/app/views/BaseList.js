'use strict';
app.BaseListView = app.BaseView.extend({

    render: function (collection) {
        this.collection = collection;
        var attributes = this.options ? this.options : {};
        if (collection) {
            attributes.objects = collection.toJSON();
        }
        this.$el.html(this.template(attributes));

        return this;
    }
});