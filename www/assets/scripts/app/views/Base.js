'use strict';
app.BaseView = Backbone.View.extend({

    template_name: '',
    alert_container: '.alert-container',

    template: function (data) {
        return _.template(app.template_cache.get(this.template_name), data);
    },

    initialize: function (options) {
        this.options = options;
    },

    render: function () {
        var attributes = this.options ? this.options : {};
        if (this.model) {
            attributes = $.extend(attributes, this.model.toJSON());
        }
        this.$el.html(this.template(attributes));

        return this;
    },

    destroy: function (event, thisModel, callback, el) {
        var me = this;

        if (!el || el.length < 1) {
            el = this.$el;
        }

        if (!thisModel) {
            thisModel = this.model;
        }

        thisModel.destroy({
            success: function (model, response) {
                if (response && response.message) {
                    var alert = el.find('.submit-container .alert-success');
                    alert.html('<strong>Success: </strong> ' + response.message).show();
                    alert.delay(3000).fadeOut();
                }
                me.trigger('save_complete', event, model);

                if (callback) {
                    callback(response);
                }

            },
            error: function (model, xhr) {
                var alert = el.find('.submit-container .alert-danger');

                var errorMsg = '';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else {
                    errorMsg = '<strong>Error: </strong> There was an error with your submission.  Please try again';
                }

                app.log(errorMsg, {
                    method: 'BaseView.destroy',
                    model: model,
                    xhr: xhr
                });

                alert.html(errorMsg).show();
            }
        });
    },

    save: function (event, thisModel, callback, el, attributes) {

        if (!el || el.length < 1) {
            el = this.$el;
        }

        if (!attributes) {
            attributes = {};
        }

        el.find('form').validate();

        if (el.find('form').valid()) {
            attributes = $.extend(attributes, el.find('form').serializeObject());
            var me = this;

            var submit_button = el.find('.submit-container button.submit');
            submit_button.button('loading');
            el.find('.submit-container .alert').hide();

            if (!thisModel) {
                thisModel = this.model;
            }

            thisModel.save(attributes, {
                success: function (model, response) {
                    if (response && response.message) {
                        var alert = el.find('.submit-container .alert-success');
                        alert.html('<strong>Success: </strong> ' + response.message).show();
                        alert.delay(3000).fadeOut();

                        if (response.data && response.data.uuid) {
                            thisModel.set('uuid', response.data.uuid);
                        }
                    }
                    submit_button.button('reset');

                    if (callback) {
                        callback(response);
                    }

                    me.trigger('save_complete');
                },
                error: function (model, xhr) {
                    var alert = el.find('.submit-container .alert-danger');

                    var errorMsg = '';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else {
                        errorMsg = '<strong>Error: </strong> There was an error with your submission.  Please try again';
                    }

                    app.log(errorMsg, {
                        method: 'BaseView.save',
                        model: model,
                        xhr: xhr
                    });

                    alert.html(errorMsg).show();
                    submit_button.button('reset');
                }

            });
        }
        return false;
    }
});