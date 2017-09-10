'use strict';
app.PlanListCollection = app.BaseListCollection.extend({
    model: app.Plan,

    url: function() {
        return app.rest_root + 'plans/';
    }
});