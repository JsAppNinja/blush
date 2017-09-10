'use strict';
app.BaseRouter = Backbone.Router.extend({
    container: '#backbone-container',
    alert_container: '.alert-container',
    template_cache: {},
    /* Non-routing stuff */

    start: function (root, defaultRoute) {
        if (!Modernizr.history) {
            Backbone.history.start({ hashChange: true, root: root });
            if(defaultRoute) {
                Backbone.history.loadUrl(defaultRoute);
            }
        } else {
            Backbone.history.start({ pushState: true, root: root  });
        }
    },

    renderViewComplex : function(view, callback) {
        app.template_cache.load([view.template_name], function () {
            view.render();
            if(callback) {
                callback();
            }
        });
    },

    renderView: function (view, container, callback) {
        if(!container) {
            container = this.container;
        }
        app.template_cache.load([view.template_name], function () {
            $(container).empty();
            view.render().$el.appendTo(container);

            if(callback) {
                callback();
            }
        });
    },

    renderCollectionView: function (view, collection, container, callback) {
        if(!container) {
            container = this.container;
        }
        app.template_cache.load([view.template_name], function () {
            $(container).empty();
            view.render(collection).$el.appendTo(container);

            if(callback) {
                callback();
            }
        });
    },

    confirm : function(title, text, callback) {
        if(this.confirmModalView) {
            this.confirmModalView.remove();
            this.confirmView.remove();
        }           /* Create a new view */
        this.confirmView = new app.ConfirmView({
            text: text
        });
        this.confirmModalView = new Backbone.BootstrapModal({
            content: this.confirmView,
            title: title,
            modalClass: 'confirm',
            okText: 'Yes',
            cancelText: 'No',
            okCloses: true
        }).open();

        this.confirmModalView.on('ok', callback);
    }
});