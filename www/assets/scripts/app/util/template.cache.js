'use strict';
/* Create the template cache */
app.template_cache = {
    templates: {},

    get: function (path) {
        return this.templates[path];
    },

    set: function (path, data) {
        this.templates[path] = data;
    },

    remove: function (path) {
        delete this.templates[path];
    },

    path_to_id : function(path) {
        return path.replace('/', '-');
    },

    /**
     * Loads the template remotely if it needs to (it it isn't in the cache
     * @param path
     */
    load: function (paths, callback) {
        if (paths) {
            /* Look and see if any of them are cached locally */
            var paths_to_fetch = [];
            _.each(paths, function (path) {
                /* Are they in the local cache? */
                if (!app.template_cache.get(path)) {
                    paths_to_fetch.push(path);
                }
            });

            var counter = 0;

            if (paths_to_fetch.length > 0) {
                _.each(paths_to_fetch, function (path) {

                    $.get(SITE_URL + 'partials/' + path, function (data) {
                        counter++;

                        app.template_cache.set(path, data);
                        if (counter >= paths_to_fetch.length) {
                            callback();
                        }
                    });
                });
            } else {
                callback();
            }
        }
    }
};