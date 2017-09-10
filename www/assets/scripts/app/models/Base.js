'use strict';
app.BaseModel = Backbone.Model.extend({
    idAttribute: 'uuid',

    initialize: function (options) {
        if (options && options.urlRoot) {
            this.urlRoot = options.urlRoot;
        }
        Backbone.Model.prototype.initialize.call(this, options);
    }
});