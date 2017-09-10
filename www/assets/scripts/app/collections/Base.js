'use strict';
app.BaseListCollection = Backbone.Collection.extend({
    initialize: function (options) {
        if(options) {
            this.view = options.view;
            this.options = options;
        }
    }
});