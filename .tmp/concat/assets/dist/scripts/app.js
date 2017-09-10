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
'use strict';
(function ($) {
    $.fn.serializeObject = function () {

        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                validate: /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                key: /[a-zA-Z0-9_]+|(?=\[\])/g,
                push: /^$/,
                fixed: /^\d+$/,
                named: /^[a-zA-Z0-9_]+$/
            };


        this.build = function (base, key, value) {
            base[key] = value;
            return base;
        };

        this.push_counter = function (key) {
            if (push_counters[key] === undefined) {
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        /* Serialize all of the form values into an array */
        var serializedValues = $(this).serializeArray();

        /* Add any unchecked values to switch them off */
        var unchecked = $.map($(this).find('input[type="checkbox"]:not(:checked)'),
            function(check) {
                return { name : check.name, value: 0 };
            }
        );
        serializedValues = serializedValues.concat(unchecked);

        $.each(serializedValues, function () {

            // skip invalid keys
            if (!patterns.validate.test(this.name)) {
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while ((k = keys.pop()) !== undefined) {

                // adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp('\\[' + k + '\\]$'), '');

                // push
                if (k.match(patterns.push)) {
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // fixed
                else if (k.match(patterns.fixed)) {
                    merge = self.build([], k, merge);
                }

                // named
                else if (k.match(patterns.named)) {
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };
})(jQuery);
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
'use strict';
app.BaseListCollection = Backbone.Collection.extend({
    initialize: function (options) {
        if(options) {
            this.view = options.view;
            this.options = options;
        }
    }
});
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
'use strict';
app.FourOhFourView = app.BaseView.extend({
    id: 'file-not-found',
    className: 'file-not-found',
    default_template_name: '404'
});

'use strict';
/**
 * Bootstrap Modal wrapper for use with Backbone.
 *
 * Takes care of instantiation, manages multiple modals,
 * adds several options and removes the element from the DOM when closed
 *
 * @author Charles Davison <charlie@powmedia.co.uk>
 *
 * Events:
 * shown: Fired when the modal has finished animating in
 * hidden: Fired when the modal has finished animating out
 * cancel: The user dismissed the modal
 * ok: The user clicked OK
 */
(function ($, _, Backbone) {

    //Set custom template settings
    var _interpolateBackup = _.templateSettings;
    _.templateSettings = {
        interpolate: /\{\{(.+?)\}\}/g,
        evaluate: /<%([\s\S]+?)%>/g
    };

    var template = _.template(
        '<div class="modal-dialog {{modalClass}}">' +
            '<div class="modal-content">' +
            '<% if (title) { %>' +
            '<div class="modal-header">' +
            '<% if (allowCancel) { %>' +
            '<a class="close">&times;</a>' +
            '<% } %>' +
            '<h3>{{title}}</h3>' +
            '</div>' +
            '<% } %>' +
            '<div class="modal-body">{{content}}</div>' +
            '<div class="modal-footer">' +
            '<% if (allowCancel) { %>' +
            '<% if (cancelText) { %>' +
            '<a href="#" class="btn cancel">{{cancelText}}</a>' +
            '<% } %>' +
            '<% } %>' +
            '<% if(allowOk) { %>' +
            '<a href="#" class="btn ok btn-primary">{{okText}}</a>' +
            '<% } %>' +
            '</div>' +
            '</div>' +
            '</div>');

    //Reset to users' template settings
    _.templateSettings = _interpolateBackup;


    var Modal = Backbone.View.extend({

        className: 'modal',

        events: {
            'click .close': function (event) {
                event.preventDefault();

                this.trigger('cancel');

                if (this.options.content && this.options.content.trigger) {
                    this.options.content.trigger('cancel', this);
                }
            },
            'click .cancel': function (event) {
                event.preventDefault();

                this.trigger('cancel');

                if (this.options.content && this.options.content.trigger) {
                    this.options.content.trigger('cancel', this);
                }
            },
            'click .ok': function (event) {
                event.preventDefault();

                this.trigger('ok');

                if (this.options.content && this.options.content.trigger) {
                    this.options.content.trigger('ok', this);
                }

                if (this.options.okCloses) {
                    this.close();
                }
            }
        },

        /**
         * Creates an instance of a Bootstrap Modal
         *
         * @see http://twitter.github.com/bootstrap/javascript.html#modals
         *
         * @param {Object} options
         * @param {String|View} [options.content] Modal content. Default: none
         * @param {String} [options.title]        Title. Default: none
         * @param {String} [options.okText]       Text for the OK button. Default: 'OK'
         * @param {String} [options.cancelText]   Text for the cancel button. Default: 'Cancel'. If passed a falsey value, the button will be removed
         * @param {Boolean} [options.allowCancel  Whether the modal can be closed, other than by pressing OK. Default: true
         * @param {Boolean} [options.escape]      Whether the 'esc' key can dismiss the modal. Default: true, but false if options.cancellable is true
         * @param {Boolean} [options.animate]     Whether to animate in/out. Default: false
         * @param {Function} [options.template]   Compiled underscore template to override the default one
         */
        initialize: function (options) {
            this.options = _.extend({
                title: null,
                okText: 'OK',
                focusOk: true,
                allowOk: true,
                okCloses: true,
                cancelText: 'Cancel',
                allowCancel: true,
                escape: true,
                animate: false,
                modalClass: '',
                template: template
            }, options);
        },

        /**
         * Creates the DOM element
         *
         * @api private
         */
        render: function () {
            var $el = this.$el,
                options = this.options,
                content = options.content;

            //Create the modal container
            $el.html(options.template(options));

            //Insert the main content if it's a view
            if (content.$el) {
                content.render();
                $el.find('.modal-body').html(content.$el);
            }

            if (options.animate) {
                $el.addClass('fade');
            }

            this.isRendered = true;

            return this;
        },

        /**
         * Renders and shows the modal
         *
         * @param {Function} [cb]     Optional callback that runs only when OK is pressed.
         */
        open: function (cb) {
            if (!this.isRendered) {
                this.render();
            }

            var self = this,
                $el = this.$el;

            //Create it
            $el.modal(_.extend({
                keyboard: this.options.allowCancel,
                backdrop: this.options.allowCancel ? true : 'static'
            }, this.options.modalOptions));

            //Focus OK button
            $el.one('shown', function () {
                if (self.options.focusOk) {
                    $el.find('.btn.ok').focus();
                }

                if (self.options.content && self.options.content.trigger) {
                    self.options.content.trigger('shown', self);
                }

                self.trigger('shown');
            });

            //Adjust the modal and backdrop z-index; for dealing with multiple modals
            var numModals = Modal.count,
                $backdrop = $('.modal-backdrop:eq(' + numModals + ')'),
                backdropIndex = parseInt($backdrop.css('z-index'), 10),
                elIndex = parseInt($backdrop.css('z-index'), 10);

            $backdrop.css('z-index', backdropIndex + numModals);
            this.$el.css('z-index', elIndex + numModals);

            if (this.options.allowCancel) {
                $backdrop.one('click', function () {
                    if (self.options.content && self.options.content.trigger) {
                        self.options.content.trigger('cancel', self);
                    }

                    self.trigger('cancel');
                });

                $(document).one('keyup.dismiss.modal', function (e) {
                    if(e.which === 27) {
                        self.trigger('cancel');
                    }

                    if (self.options.content && self.options.content.trigger) {
                        if(e.which === 27) {
                            self.options.content.trigger('shown', self);
                        }
                    }
                });
            }

            this.on('cancel', function () {
                self.close();
            });

            Modal.count++;

            //Run callback on OK if provided
            if (cb) {
                self.on('ok', cb);
            }

            return this;
        },

        /**
         * Closes the modal
         */
        close: function () {
            var self = this,
                $el = this.$el;

            //Check if the modal should stay open
            if (this._preventClose) {
                this._preventClose = false;
                return;
            }

            $el.one('hidden', function onHidden(e) {
                // Ignore events propagated from interior objects, like bootstrap tooltips
                if (e.target !== e.currentTarget) {
                    return $el.one('hidden', onHidden);
                }
                self.remove();

                if (self.options.content && self.options.content.trigger) {
                    self.options.content.trigger('hidden', self);
                }

                self.trigger('hidden');
            });

            $el.modal('hide');

            Modal.count--;
        },

        /**
         * Stop the modal from closing.
         * Can be called from within a 'close' or 'ok' event listener.
         */
        preventClose: function () {
            this._preventClose = true;
        }
    }, {
        //STATICS

        //The number of modals on display
        count: 0
    });


    //EXPORTS
    //CommonJS
    if (typeof require === 'function' && typeof module !== 'undefined' && exports) {
        module.exports = Modal;
    }

    //Regular; add to Backbone.Bootstrap.Modal
    else {
        Backbone.BootstrapModal = Modal;
    }

})(jQuery, _, Backbone);

'use strict';
app.BaseFormView = app.BaseView.extend({

    render: function () {
        app.BaseView.prototype.render.call(this);

        var me = this;
        if(me.model) {
            this.$el.find('select').each(function () {
                var val = me.model.get($(this).attr('name'));
                if (typeof val !== undefined) {
                    $(this).val(val);
                }
            });
        }

        /* Setup the form validation */
        this.$el.find('form').validate();

        return this;
    }

});
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
'use strict';
app.DataTableView = app.BaseView.extend({

    template_name: 'admin/datatable',

    table: undefined,

    events: {
        'click .dataTable tr td': 'edit'
    },

    initialize: function (options) {
        options = options || {};
        this.options = _.extend({
            remote: true,
            method: 'POST',
            perPage: 20,
            columns: [],
            sorting: []
        }, options);
    },

    render: function () {
        this.$el.html(this.template(this.options));

        this.render_table();
        /* Build the datatable */

        return this;
    },

    get_filter_params: function () {
        return;
    },

    render_table: function () {
        var me = this;

        this.table = this.$el.find('#datatable').dataTable({
            sAjaxSource: this.options.url,
            bServerSide: this.options.remote,
            sServerMethod: this.options.method,
            iDisplayLength: this.options.perPage,
            aoColumns: this.options.columns,
            aaSorting: this.options.sorting,
            fnServerParams: function(aodata) {
                me.get_filter_params(aodata);
            }
        });
    },

    edit: function (event) {
        var id = $(event.currentTarget).closest('tr').attr('id');
        app.router.navigate(this.options.edit_url + id, true);
        return false;
    }
});
'use strict';
app.Availability = app.BaseModel.extend({
    urlRoot: app.rest_root + 'users/availability'
});
'use strict';
app.AvailabilityCalendar = app.BaseModel.extend({
    idAttribute: 'id',
    urlRoot: app.rest_root + 'users/availability_calendar'
});
'use strict';
app.Conversation = app.BaseModel.extend({
    urlRoot: app.rest_root + 'conversations/conversation'
});
'use strict';
app.Customer = app.BaseModel.extend({
    urlRoot: app.rest_root + 'users/customer'
});
'use strict';
app.Dashboard = app.BaseModel.extend({
    defaults: {
        counselors: 0,
        customers: 0,
        requests: 0,
        transactions: 0,

        last_30_customers: 0,
        last_30_counselors: 0,
        last_30_requests: 0,
        last_30_transactions: 0,
        last_30_money: 0
    },

    urlRoot: app.rest_root + 'admin/dashboard'
});
'use strict';
app.Diary = app.BaseModel.extend({
    urlRoot: app.rest_root + 'diaries/diary',
    defaults: {
        draft: 1
    }
});
'use strict';
app.Event = app.BaseModel.extend({
    urlRoot: app.rest_root + 'events/event'
});
'use strict';
app.Message = app.BaseModel.extend({
    urlRoot: app.rest_root + 'messages/message'
});
'use strict';
app.Note = app.BaseModel.extend({
    urlRoot: app.rest_root + 'notes/note'
});
'use strict';
app.Notification = app.BaseModel.extend({
    urlRoot: app.rest_root + 'notifications/notification'
});
'use strict';
app.Payable = app.BaseModel.extend({
    urlRoot: app.rest_root + 'admin/payables/payable'
});
'use strict';
app.Payout = app.BaseModel.extend({
    urlRoot: app.rest_root + 'admin/payouts/payout'
});
'use strict';
app.Plan = app.BaseModel.extend({
    urlRoot: app.rest_root + 'plans'
});
'use strict';
app.Pricing = app.BaseModel.extend({
    urlRoot: app.rest_root + 'plans/pricing'
});
'use strict';
app.Registration = app.BaseModel.extend({

    defaults: {
        step: 1,
        completed: 0,
        firstname: '',
        lastname: '',
        email: '',
        birthday: '',
        mobile_phone: '',
        gender: '',
        referral: '',
        referral_counselor: '',
        referral_other: '',
        address: '',
        state: '',
        zipcode: '',
        city: '',
        school_occupation: '',
        username: '',
        password: '',
        preferred_coach: '',
        preferred_coaching_time: '',
        coaching_qualifications: '',
        parent_email: '',
        parent_consent: 0,

        /* Step 3 */
        counselor_before_more: '',
        drugs_alcohol_more: '',
        sleeping_changes_more: '',
        medical_diagnosis_more: '',
        suicide_homicide_more: '',

        /* Step 4 */
        pop_culture: '',
        interest: '',
        dream: '',
        family: '',
        focus: ''
    },

    urlRoot: function() {
        if(this.user_uuid) {
            return app.rest_root + 'registrations/user/'+this.user_uuid;
        } else {
            return app.rest_root + 'registrations/registration';
        }
    }
});
'use strict';
app.Transaction = app.BaseModel.extend({
    urlRoot: app.rest_root + 'transactions/transaction'
});
'use strict';
app.User = app.BaseModel.extend({
    urlRoot: app.rest_root + 'users/user'
});
'use strict';
app.ConversationListCollection = app.BaseListCollection.extend({
    model: app.Conversation,
    url: app.rest_root + 'conversations'
});
'use strict';
app.CustomerListCollection = app.BaseListCollection.extend({
    model: app.Customer,
    url: app.rest_root + 'customers'
});
'use strict';
app.DiaryListCollection = app.BaseListCollection.extend({
    model: app.Diary,
    url: function() {
        if(this.uuid) {
            return app.rest_root + 'diaries/user/'+this.uuid;
        } else {
            return app.rest_root + 'diaries/';
        }
    }
});
'use strict';
app.EventListCollection = app.BaseListCollection.extend({
    model: app.Event,

    url: function() {
        if(this.uuid) {
            return app.rest_root + 'events/user/'+this.uuid;
        } else {
            return app.rest_root + 'events/';
        }
    }
});
'use strict';
app.MessageListCollection = app.BaseListCollection.extend({
    model: app.Message,

    url: function() {
        if(this.uuid) {
            return app.rest_root + 'messages/conversation/'+this.uuid;
        } else {
            return app.rest_root + 'messages/';
        }
    }
});
'use strict';
app.NoteListCollection = app.BaseListCollection.extend({
    model: app.Note,

    url: function() {
        if(this.uuid) {
            return app.rest_root + 'notes/user/'+this.uuid;
        } else {
            return app.rest_root + 'notes/';
        }
    }
});
'use strict';
app.PlanListCollection = app.BaseListCollection.extend({
    model: app.Plan,

    url: function() {
        return app.rest_root + 'plans/';
    }
});
'use strict';
app.DashboardCounselorRouter = app.BaseRouter.extend({
    container: '#dashboard-container',

    root_url: app.app_root + 'dashboard/',

    routes: {
        'calendar': 'calendar',
        'calendar/': 'calendar',

        'conversations': 'conversation',
        'conversations/': 'conversation',
        'conversation/:id': 'conversation',
        'conversation/:id/': 'conversation',

        'message': 'message',
        'message/': 'message',

        'customer': 'customer',
        'customer/': 'customer',
        'customer/:id': 'customer',
        'customer/:id/': 'customer',
        'customer/:id/:path': 'customer',
        'customer/:id/:path/': 'customer',

        'customers': 'customers',
        'customers/': 'customers',

        '*path': 'calendar'
    },

    /**
     * Private function that is called to initiate the dashboard as a whole
     * @private
     */
    _dashboard: function (callback) {
        var me = this;
        /* Load the templates up into the template cache and then kick off the main view */
        if (!this.dashboardView) {
            this.user = new app.User();
            this.user.fetch({
                success: function() {
                    me.dashboardView = new app.DashboardCounselorView({
                        model : me.user
                    });
                    me.renderViewComplex(me.dashboardView, callback);
                }
            });
        } else {
            callback();
        }
    },

    /**
     * Loads the calendar and the list of events
     */
    calendar: function () {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.calendarView) {
                me.calendarView.remove();
            }

            /* Create a new view */
            me.calendarView = new app.CalendarView();
            me.renderView(me.calendarView, undefined, function() {
                me.calendarView.build_calendar();
            });
        });


    },

    /**
     * Loads the list of customers
     */
    customers: function () {
        var me = this;

        me._dashboard(function() {
            /* Kill the old view */
            if (me.customerListView) {
                me.customerListView.remove();
            }
            me.customerListView = new app.CustomerListView();

            if (!me.customerListCollection) {
                me.customerListCollection = new app.CustomerListCollection([], {
                    view: me.customerListView
                });
            }
            me.customerListCollection.fetch({
                success: function (collection) {
                    me.renderCollectionView(me.customerListView, collection);
                }
            });
        });


    },

    customer: function (customerId, path) {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.customerView) {
                me.customerView.remove();
            }

            /* Create a new view */
            me.customerView = new app.CustomerView({
                path: path,
                model: new app.Customer({
                    uuid: customerId
                })
            });
            /* Fetch calls the render */
            me.customerView.model.fetch({
                success: function () {
                    me.renderView(me.customerView);
                }
            });
        });
    },

    conversations : function() {

    },

    /**
     * Loads the dashboard and the list of conversations.  The conversation uuid can be null if the user has not clicked on
     * a conversation
     */
    conversation: function (conversation_uuid) {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.conversationListView) {
                me.conversationListView.remove();
            }
            me.conversationListView = new app.ConversationListView({
                conversation_uuid : conversation_uuid
            });

            if (!me.conversationListCollection) {
                me.conversationListCollection = new app.ConversationListCollection([], {
                    view: me.conversationListView
                });
            }
            me.conversationListCollection.fetch({
                success: function (collection) {
                    me.renderCollectionView(me.conversationListView, collection);
                }
            });
        });

    },

    message: function () {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.messageView) {
                me.messageView.remove();
            }

            /* Create a new view */
            me.messageView = new app.MessageView({
                model: new app.Message()
            });
            me.messageView.cancel_url = app.router.root_url + 'conversations/';
            /* Fetch calls the render */
            me.messageView.model.fetch({
                success: function () {
                    me.renderView(me.messageView, undefined, function() {
                        /* Scroll down to the diary window if necessary */
                        var targetOffset = $('#message').offset().top;
                        $('html,body').animate({scrollTop: targetOffset}, 300);
                    });
                }
            });
        });
    }
});

if(app.activeRouter==='dashboardCounselor') {
    app.dashboardCounselorRouter = new app.DashboardCounselorRouter();
    app.router = app.dashboardCounselorRouter;
    $(document).ready(function() {
        app.dashboardCounselorRouter.start(app.app_root+'dashboard/');
    });
}
'use strict';
app.DashboardCustomerRouter = app.BaseRouter.extend({
    container: '#dashboard-container',

    root_url: app.app_root + 'dashboard/',

    routes: {
        'events': 'events',
        'events/': 'events',

        'messages': 'messages',
        'messages/': 'messages',

        'message': 'message',
        'message/': 'message',

        'diary': 'diary',
        'diary/': 'diary',
        'diary/:id': 'diary',
        'diary/:id/': 'diary',

        'event/:id': 'event',
        'event/:id/': 'event',

        'add-video': 'add_video',
        'add-video/': 'add_video',

        'diaries': 'diaries',
        'diaries/': 'diaries',

        '*path': 'events'
    },

    /**
     * Private function that is called to initiate the dashboard as a whole
     * @private
     */
    _dashboard: function (callback) {
        var me = this;
        /* Load the templates up into the template cache and then kick off the main view */
        if (!this.dashboardView) {
            this.user = new app.User();
            this.user.fetch({
                success: function() {
                    me.dashboardView = new app.DashboardCustomerView({
                        model : me.user
                    });
                    me.renderViewComplex(me.dashboardView, callback);
                }
            });
        } else {
            callback();
        }
    },
    /**
     * Loads the dashboard and the list of events
     */
    events: function () {
        var me = this;
        me._dashboard(function() {
            /* Kill the old view */
            if (me.eventListView) {
                me.eventListView.remove();
            }
            me.eventListView = new app.EventListView();

            if (!me.eventListCollection) {
                me.eventListCollection = new app.EventListCollection([], {
                    view: me.eventListView
                });
            }
            me.eventListCollection.fetch({
                success: function (collection) {
                    me.renderCollectionView(me.eventListView, collection);
                }
            });
        });
    },

    event: function (eventId) {
        var me = this;

        me._dashboard(function () {
            /* Kill the old view */
            if (me.eventView) {
                me.eventView.remove();
            }

            /* Create a new view */
            me.eventView = new app.EventView({
                model: new app.Event({
                    uuid: eventId
                })
            });
            /* Fetch calls the render */
            me.eventView.model.fetch({
                success: function () {
                    me.renderView(me.eventView, undefined, function() {
                        /* Scroll down to the diary window if necessary */
                        var targetOffset = $('#event').offset().top;
                        $('html,body').animate({scrollTop: targetOffset}, 300);
                    });
                }
            });
        });
    },

    add_video: function() {
        var me = this;

        me._dashboard(function () {
            if((parseInt(app.user.credits) - parseInt(app.user.pending_credits))>=app.data.credits_counseling) {
                /* Kill the old view */
                if (me.videoAddView) {
                    me.videoAddView.remove();
                }

                /* Create a new view */

                me.availability = new app.Availability();
                me.availability.fetch({
                    success: function () {
                        me.videoAddView = new app.VideoAddView({
                            availability: me.availability
                        });
                        me.renderView(me.videoAddView, undefined, function() {
                            /* Scroll down to the diary window if necessary */
                            var targetOffset = $('#video_add').offset().top;
                            $('html,body').animate({scrollTop: targetOffset}, 300);
                        });

                    }
                });
            } else {
                app.error_message(app.msg_error.credits_invalid_video, me.alert_container);
            }
        });
    },

    diary: function (diaryId) {
        var me = this;

        me._dashboard(function () {
            if(!app.user.counselor.uuid) {
                app.error_message(app.msg_error.no_counselor, me.alert_container);
            } else if(diaryId || (parseInt(app.user.credits) - parseInt(app.user.pending_credits))>=app.data.credits_diary) {
                /* Kill the old view */
                if (me.diaryView) {
                    me.diaryView.remove();
                }

                /* Create a new view */
                me.diaryView = new app.DiaryView({
                    model: new app.Diary({
                        uuid: diaryId
                    })
                });
                /* Fetch calls the render */
                me.diaryView.model.fetch({
                    success: function () {
                        me.renderView(me.diaryView, undefined, function() {
                            /* Scroll down to the diary window if necessary */
                            var targetOffset = $('#diary').offset().top;
                            $('html,body').animate({scrollTop: targetOffset}, 300);
                        });
                    }
                });
            } else {
                app.error_message(app.msg_error.credits_invalid_diary, me.alert_container);
            }
        });
    },

    /**
     * Loads the dashboard and the list of diaries
     */
    diaries: function () {
        var me = this;

        me._dashboard(function () {

            /* Kill the old view */
            if (me.diaryListView) {
                me.diaryListView.remove();
            }
            me.diaryListView = new app.DiaryListCustomerView();

            if (!me.diaryListCollection) {
                me.diaryListCollection = new app.DiaryListCollection([], {
                    view: me.diaryListView
                });
            }
            me.diaryListCollection.fetch({
                success: function (collection) {
                    me.renderCollectionView(me.diaryListView, collection);
                }
            });
        });
    },

    /**
     * Loads the dashboard and the list of diaries
     */
    messages: function () {
        var me = this;

        me._dashboard(function () {

            /* Get the conversation between this user and their coach */
            $.get(app.rest_root+'conversations/conversation', {}, function(data) {

                var conversation_uuid = 0;
                if(data && data.uuid) {
                    conversation_uuid = data.uuid;
                }

                /* Kill the old view */
                if (me.messageListView) {
                    me.messageListView.remove();
                }
                me.messageListView = new app.MessageListView({
                    conversation_uuid : conversation_uuid
                });

                if (!me.messageListCollection) {
                    me.messageListCollection = new app.MessageListCollection([], {
                        view: me.messageListView
                    });
                }
                me.messageListCollection.fetch({
                    success: function (collection) {


                        me.renderCollectionView(me.messageListView, collection);
                    }
                });
            });

        });
    },

    message: function () {
        var me = this;
        me._dashboard(function () {
            if(!app.user.counselor.uuid) {
                app.error_message(app.msg_error.no_counselor, me.alert_container);
            } else {
                /* Kill the old view */
                if (me.messageView) {
                    me.messageView.remove();
                }

                /* Create a new view */
                me.messageView = new app.MessageView({
                    model: new app.Message()
                });
                /* Fetch calls the render */
                me.messageView.model.fetch({
                    success: function () {
                        me.renderView(me.messageView, undefined, function() {

                        });

                        /* Scroll down to the diary window if necessary */
                        var targetOffset = $('#message').offset().top;
                        $('html,body').animate({scrollTop: targetOffset}, 300);
                    }
                });
            }
        });

    }
});

if(app.activeRouter==='dashboardCustomer') {
    app.dashboardCustomerRouter = new app.DashboardCustomerRouter();
    app.router = app.dashboardCustomerRouter;
    $(document).ready(function() {
        app.dashboardCustomerRouter.start(app.app_root+'dashboard/');
    });
}
'use strict';
app.MyAccountRouter = app.BaseRouter.extend({
    container: '#my-account-container',
    root_url: app.app_root + 'my_account/',

    routes: {
        'profile': 'profile',
        'profile/': 'profile',

        'notifications': 'notifications',
        'notifications/': 'notifications',

        'password': 'password',
        'password/': 'password',

        'payment': 'payment',
        'payment/': 'payment',

        'account_type': 'account_type',
        'account_type/': 'account_type',

        'availability': 'availability',
        'availability/': 'availability',
        '*path': 'profile'
    },

    /**
     * Private function that is called to initiate the dashboard as a whole
     * @private
     */
    _my_account: function (callback) {
        /* Load the templates up into the template cache and then kick off the main view */
        if (!this.myAccountView) {
            this.myAccountView = new app.MyAccountView();
            this.renderViewComplex(this.myAccountView, callback);
        } else {
            callback();
        }
    },

    _get_user: function (callback) {
        if (!this.user) {
            this.user = new app.User();
            this.user.fetch({
                success: callback
            });
        } else {
            callback();
        }
    },

    profile: function () {
        var me = this;
        /* Kill the old view */
        if (me.profileView) {
            me.profileView.remove();
        }

        this._get_user(function () {

            me.registration = new app.Registration();
            me.registration.user_uuid = app.user.uuid;

            me._my_account(function() {
                me.registration.fetch({
                    success: function() {
                        me.profileView = new app.ProfileView({
                            model: me.user,
                            registration : me.registration.toJSON()
                        });
                        /* Store the actual model on the profile view so we can use it when saving */
                        me.profileView.registration = me.registration;
                        me.renderView(me.profileView);
                    }
                });
            });
        });
    },

    notifications: function () {
        var me = this;
        /* Kill the old view */
        if (me.notificationsView) {
            me.notificationsView.remove();
        }
        this._get_user(function () {
            me._my_account(function() {
                me.notificationsView = new app.NotificationsView({
                    model: me.user
                });
                me.renderView(me.notificationsView);
            });
        });
    },

    password: function () {
        var me = this;
        /* Kill the old view */
        if (me.passwordView) {
            me.passwordView.remove();
        }
        this._get_user(function () {
            me._my_account(function() {
                me.passwordView = new app.PasswordView({
                    model: me.user
                });
                me.renderView(me.passwordView);
            });
        });
    },

    account_type: function () {
        var me = this;
        /* Kill the old view */
        if (me.accountTypeView) {
            me.accountTypeView.remove();
        }
        this._get_user(function () {
            me._my_account(function() {

                me.pricing = new app.Pricing();
                me.pricing.fetch({
                    success: function () {
                        me.accountTypeView = new app.AccountTypeView({
                            model: me.user,
                            pricing: me.pricing
                        });
                        me.renderView(me.accountTypeView);
                    }
                });
            });
        });
    },

    availability: function () {
        var me = this;
        /* Kill the old view */
        if (me.availabilityView) {
            me.availabilityView.remove();
        }
        this._get_user(function () {
            me._my_account(function() {

                me.availability = new app.Availability();
                me.availability.fetch({
                    success: function () {
                        me.availabilityView = new app.AvailabilityView({
                            model: me.user,
                            availability: me.availability
                        });
                        me.renderView(me.availabilityView);
                    }
                });
            });
        });
    },

    payment: function () {
        var me = this;
        /* Kill the old view */
        if (me.paymentView) {
            me.paymentView.remove();

            /* Force reload of the template */
            app.template_cache.remove('my_account/payment');
        }

        this._get_user(function () {
            me._my_account(function() {
                me.paymentView = new app.PaymentView({
                    model: me.user
                });
                me.renderView(me.paymentView);
            });
        });
    }
});

if(app.activeRouter==='myAccount') {
    app.myAccountRouter = new app.MyAccountRouter();
    app.router = app.myAccountRouter;
    $(document).ready(function() {
        app.myAccountRouter.start(app.app_root+'my_account/');
    });
}
'use strict';
app.AdminRouter = app.BaseRouter.extend({
    root_url: app.admin_root,

    routes: {

        'counselors': 'counselors',
        'counselors/': 'counselors',

        'counselor': 'counselor',
        'counselor/:id': 'counselor',

        'customers': 'customers',
        'customers/': 'customers',

        'customer': 'customer',
        'customer/:id': 'customer',

        'requests': 'requests',
        'requests/': 'requests',

        'transactions': 'transactions',
        'transactions/': 'transactions',

        'transaction': 'transaction',
        'transaction/:id': 'transaction',

        'payouts': 'payouts',
        'payouts/': 'payouts',

        'payout': 'payout',
        'payout/:id': 'payout',

        'payables': 'payables',
        'payables/': 'payables',

        'payable': 'payable',
        'payable/:id': 'payable',

        'notifications': 'notifications',
        'notifications/': 'notifications',

        'notification': 'notification',
        'notification/:id': 'notification',

        'pricing': 'pricing',

        '*path': 'dashboard'
    },

    /**
     * Private function that is called to initiate the dashboard as a whole
     * @private
     */
    _admin: function (page_title, active_class, btn_text, btn_callback) {
        /* Load the templates up into the template cache and then kick off the main view */
        if (!this.adminView) {
            this.adminView = new app.admin.AdminView({ });
            this.adminView.render();
        }
        this.adminView.set_page(page_title, active_class, btn_text, btn_callback);
    },

    dashboard: function () {
        var me = this;

        app.template_cache.load(['admin/admin', 'admin/dashboard'], function () {
            me._admin('Dashboard', 'dashboard');

            /* Kill the old view */
            if (me.dashboardView) {
                me.dashboardView.remove();
            }

            /* Create a new view */
            me.dashboardView = new app.admin.DashboardView({
                model: new app.Dashboard({})
            });
            /* Fetch calls the render */
            me.dashboardView.model.fetch({
                success: function () {
                    me.renderView(me.dashboardView);
                }
            });
        });
    },

    /**
     * Loads the list of counselors
     */
    counselors: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Coaches', 'counselors', 'Add Coach', function () {
                app.adminRouter.navigate('counselor', true);
            });

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/users/counselors',
                edit_url: 'counselor/',
                cls: 'counselors',
                columns: [
                    {
                        title: 'Last Name',
                        mDataProp: 'lastname'
                    },
                    {
                        title: 'First Name',
                        mDataProp: 'firstname'
                    },
                    {
                        title: 'Username',
                        mDataProp: 'username'
                    },
                    {
                        title: 'Created',
                        mDataProp: 'created'
                    },
                    {
                        title: 'Last Login',
                        mDataProp: 'last_login'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    counselor: function (counselorId) {
        var me = this;

        app.template_cache.load(['admin/admin', 'admin/counselor'], function () {
            if (counselorId) {
                me._admin('Edit Coach', 'counselors');
            } else {
                me._admin('Add Coach', 'counselors');
            }

            /* Kill the old view */
            if (me.counselorView) {
                me.counselorView.remove();
            }

            /* Create a new view */
            me.counselorView = new app.admin.CounselorView({
                model: new app.User({
                    urlRoot: app.rest_root + 'admin/users/user',
                    uuid: counselorId,
                    user_type_id: app.user_type_counselor
                })
            });
            /* Fetch calls the render */
            me.counselorView.model.fetch({
                success: function () {
                    me.renderView(me.counselorView);
                }
            });
        });
    },

    /**
     * Loads the list of customers
     */
    customers: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Customers', 'customers', 'Add Customer', function () {
                app.adminRouter.navigate('customer', true);
            });

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.admin.CustomerDataTableView({
                url: app.rest_root + 'admin/users/customers',
                edit_url: 'customer/',
                cls: 'customers',
                columns: [
                    {
                        title: 'Last Name',
                        mDataProp: 'lastname'
                    },
                    {
                        title: 'First Name',
                        mDataProp: 'firstname'
                    },
                    {
                        title: 'Username',
                        mDataProp: 'username'
                    },
                    {
                        title: 'Created',
                        mDataProp: 'created'
                    },
                    {
                        title: 'Last Login',
                        mDataProp: 'last_login'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    customer: function (customerId) {
        var me = this;

        app.template_cache.load(['admin/admin', 'admin/customer'], function () {
            if (customerId) {
                me._admin('Edit Customer', 'customers');
            } else {
                me._admin('Add Customer', 'customers');
            }

            /* Kill the old view */
            if (me.customerView) {
                me.customerView.remove();
            }

            /* Create a new view */
            me.customerView = new app.admin.CustomerView({
                model: new app.User({
                    urlRoot: app.rest_root + 'admin/users/user',
                    uuid: customerId,
                    user_type_id: app.user_type_customer
                })
            });
            /* Fetch calls the render */
            me.customerView.model.fetch({
                success: function () {
                    me.renderView(me.customerView);
                }
            });
        });
    },

    /**
     * Loads the list of counselors
     */
    requests: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Requests', 'requests');
        });
    },

    /**
     * Loads the list of transactions
     */
    transactions: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Transactions', 'transactions');

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/transactions',
                edit_url: 'transaction/',
                cls: 'transactions',
                sorting: [
                    [ 1, 'desc' ]
                ],
                columns: [
                    {
                        title: 'Transaction Number',
                        mDataProp: 'transaction_nbr'
                    },
                    {
                        title: 'Created',
                        mDataProp: 'created'
                    },
                    {
                        title: 'Customer',
                        mDataProp: 'customer'
                    },
                    {
                        title: 'Coach',
                        mDataProp: 'counselor'
                    },
                    {
                        title: 'Amount',
                        mDataProp: 'amount'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    transaction: function (transactionId) {
        var me = this;

        if (!transactionId) {
            app.adminRouter.navigate('transactions', true);
            return;
        }

        app.template_cache.load(['admin/admin', 'admin/transaction'], function () {
            me._admin('View Transaction', 'transactions');

            /* Kill the old view */
            if (me.transactionView) {
                me.transactionView.remove();
            }

            /* Create a new view */
            me.transactionView = new app.admin.TransactionView({
                model: new app.Transaction({
                    urlRoot: app.rest_root + 'admin/transactions/transaction',
                    uuid: transactionId
                })
            });
            /* Fetch calls the render */
            me.transactionView.model.fetch({
                success: function () {
                    me.renderView(me.transactionView);
                }
            });
        });
    },

    /**
     * Loads the list of payouts
     */
    payouts: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Past Payouts', 'payouts');

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/payouts',
                edit_url: 'payout/',
                cls: 'payouts',
                sorting: [
                    [ 1, 'desc' ]
                ],
                columns: [
                    {
                        title: 'Date',
                        mDataProp: 'created'
                    },
                    {
                        title: 'Last Name',
                        mDataProp: 'lastname'
                    },
                    {
                        title: 'First Name',
                        mDataProp: 'firstname'
                    },
                    {
                        title: 'Transaction Nbr',
                        mDataProp: 'stripe_transfer_id'
                    },
                    {
                        title: 'Amount',
                        mDataProp: 'amount'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    payout: function (payoutId) {
        var me = this;

        if (!payoutId) {
            app.adminRouter.navigate('payouts', true);
            return;
        }

        app.template_cache.load(['admin/admin', 'admin/payout'], function () {
            me._admin('Pay Coach', 'payouts');

            /* Kill the old view */
            if (me.payoutView) {
                me.payoutView.remove();
            }

            /* Create a new view */
            me.payoutView = new app.admin.PayoutView({
                model: new app.Payout({
                    urlRoot: app.rest_root + 'admin/payouts/payout',
                    uuid: payoutId
                })
            });
            /* Fetch calls the render */
            me.payoutView.model.fetch({
                success: function () {
                    me.renderView(me.payoutView);
                }
            });
        });
    },

    /**
     * Loads the list of counselors who need to be paid
     */
    payables: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Coaches to be Paid', 'payables');

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/payables',
                edit_url: 'payable/',
                cls: 'payables',
                sorting: [
                    [ 1, 'desc' ]
                ],
                columns: [
                    {
                        title: 'Last Name',
                        mDataProp: 'lastname'
                    },
                    {
                        title: 'First Name',
                        mDataProp: 'firstname'
                    },
                    {
                        title: 'Username',
                        mDataProp: 'username'
                    },
                    {
                        title: 'Amount Due',
                        mDataProp: 'amount'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    payable: function (payableId) {
        var me = this;

        if (!payableId) {
            app.adminRouter.navigate('payables', true);
            return;
        }

        app.template_cache.load(['admin/admin', 'admin/payable'], function () {
            me._admin('Pay Coach', 'payables');

            /* Kill the old view */
            if (me.payableView) {
                me.payableView.remove();
            }

            /* Create a new view */
            me.payableView = new app.admin.PayableView({
                model: new app.Payable({
                    urlRoot: app.rest_root + 'admin/payables/payable',
                    uuid: payableId
                })
            });
            /* Fetch calls the render */
            me.payableView.model.fetch({
                success: function () {
                    me.renderView(me.payableView);
                }
            });
        });
    },

    /**
     * Loads the list of counselors who need to be paid
     */
    notifications: function () {
        var me = this;
        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            me._admin('Automated Email Notification Templates', 'notifications');

            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.DataTableView({
                url: app.rest_root + 'admin/notifications',
                edit_url: 'notification/',
                cls: 'notifications',
                columns: [
                    {
                        title: 'Template',
                        mDataProp: 'name',
                        sClass : 'name'
                    },
                    {
                        title: 'Description',
                        mDataProp: 'description',
                        sClass : 'description'
                    }
                ]
            });
            /* Fetch calls the render */
            me.renderView(me.dataTableView);
        });
    },

    notification: function (notificationId) {
        var me = this;

        if (!notificationId) {
            app.adminRouter.navigate('notifications', true);
            return;
        }

        app.template_cache.load(['admin/admin', 'admin/notification'], function () {
            me._admin('Edit Notification Template', 'notifications');

            /* Kill the old view */
            if (me.notificationView) {
                me.notificationView.remove();
            }

            /* Create a new view */
            me.notificationView = new app.admin.NotificationView({
                model: new app.Notification({
                    urlRoot: app.rest_root + 'admin/notifications/notification',
                    uuid: notificationId
                })
            });
            /* Fetch calls the render */
            me.notificationView.model.fetch({
                success: function () {
                    me.renderView(me.notificationView);
                }
            });
        });
    },

    pricing: function () {
        var me = this;

        app.template_cache.load(['admin/admin', 'admin/pricing'], function () {
            me._admin('Edit Pricing', 'pricing');

            /* Kill the old view */
            if (me.pricingView) {
                me.pricingView.remove();
            }

            /* Create a new view */
            me.pricingView = new app.admin.PricingView({
                model: new app.Pricing({
                    urlRoot: app.rest_root + 'admin/plans/pricing'
                })
            });
            /* Fetch calls the render */
            me.pricingView.model.fetch({
                success: function () {
                    me.renderView(me.pricingView);
                }
            });
        });
    }
});

if(app.activeRouter==='admin') {
    app.adminRouter = new app.AdminRouter();
    app.router = app.adminRouter;
    $(document).ready(function() {
        app.adminRouter.start(app.app_root+'admin/');
    });
}
'use strict';
app.RegistrationRouter = app.BaseRouter.extend({
    root_url: app.app_root + 'accounts/registration/',

    routes: {
        'step1': 'step1',
        'step1/': 'step1',

        'step2': 'step2',
        'step2/': 'step2',

        'step3': 'step3',
        'step3/': 'step3',

        'step4': 'step4',
        'step4/': 'step4',

        'step5': 'step5',
        'step5/': 'step5',

        'plan': 'plan',
        'plan/': 'plan',

        '*path': 'step1'
    },

    _get_registration: function (callback) {
        if (!this.registration) {
            this.registration = new app.Registration();
            this.registration.fetch({
                success: callback
            });
        } else {
            callback();
        }
    },

    _step: function (step, callback) {
        var me = this;
        $('#registration-progress li a.active').removeClass('active');
        $('#registration-progress .step' + step + ' a').addClass('active');

        this._get_registration(function () {
            var template_url = 'registration/'+step;

            if(typeof step === 'number') {
                template_url = 'registration/step' + step;
            }
            app.template_cache.load([template_url], function () {
                /* Kill the old views */
                if (me.step1View) {
                    me.step1View.remove();
                }
                if (me.step2View) {
                    me.step2View.remove();
                }
                if (me.step3View) {
                    me.step3View.remove();
                }
                if (me.step4View) {
                    me.step4View.remove();
                }
                if (me.step5View) {
                    me.step5View.remove();
                }
                if (me.planView) {
                    me.planView.remove();
                }

                callback();
            });
        });
    },

    _validate_registration: function () {
        return this.registration.get('uuid');
    },

    /**
     * Load the model from the backend, clear out the container, and render the step 1 view
     */
    step1: function () {
        var me = this;
        this._step(1, function () {
            /* Create a new view */
            $.getJSON(app.rest_root + 'registrations/counselors', function(counselors) {
                me.registration.set('counselors',counselors);
                me.step1View = new app.Step1View({
                    model: me.registration
                });
                console.log(me.registration);
                /* Fetch calls the render */
                me.renderView(me.step1View);

            });
        });
    },

    /**
     * Load the model from the backend, clear out the container, and render the step 2 view
     */
    step2: function () {
        var me = this;
        this._step(2, function () {
            if (me._validate_registration()) {
                /* Create a new view */
                me.step2View = new app.Step2View({
                    model: me.registration
                });
                /* Fetch calls the render */
                me.renderView(me.step2View);
            } else {
                app.registrationRouter.navigate('step1', true);
            }
        });
    },

    /**
     * Load the model from the backend, clear out the container, and render the step 3 view
     */
    step3: function () {
        var me = this;
        this._step(3, function () {
            if (me._validate_registration()) {
                /* Create a new view */
                me.step3View = new app.Step3View({
                    model: me.registration
                });
                /* Fetch calls the render */
                me.renderView(me.step3View);
            } else {
                app.registrationRouter.navigate('step1', true);
            }
        });
    },

    /**
     * Load the model from the backend, clear out the container, and render the step 4 view
     */
    step4: function () {
        var me = this;
        this._step(4, function () {
            if (me._validate_registration()) {
                /* Create a new view */
                me.step4View = new app.Step4View({
                    model: me.registration
                });
                /* Fetch calls the render */
                me.renderView(me.step4View);
            } else {
                app.registrationRouter.navigate('step1', true);
            }
        });
    },

    /**
     * Load the model from the backend, clear out the container, and render the step 5 view
     */
    step5: function () {
        var me = this;
        this._step(5, function () {
            if (me._validate_registration()) {
                /* Create a new view */
                me.step5View = new app.Step5View({
                    model: me.registration
                });
                /* Fetch calls the render */
                me.renderView(me.step5View);
            } else {
                app.registrationRouter.navigate('step1', true);
            }
        });
    },

    /**
     * Load the model from the backend, clear out the container, and render the step 5 view
     */
    plan: function () {
        var me = this;
        this._step(2, function () {
            if (me._validate_registration()) {
                me.pricing = new app.Pricing();
                me.pricing.fetch({
                    success: function () {
                        /* Create a new view */
                        me.planView = new app.PlanView({
                            model: me.registration,
                            pricing: me.pricing
                        });
                        /* Fetch calls the render */
                        me.renderView(me.planView);
                    }
                });

            } else {
                app.registrationRouter.navigate('step1', true);
            }
        });
    }
});

if(app.activeRouter==='registration') {
    app.registrationRouter = new app.RegistrationRouter();
    app.router = app.registrationRouter;
    $(document).ready(function() {
        app.registrationRouter.start(app.app_root+'accounts/registration/');
    });
}
'use strict';
app.admin.AdminView = app.BaseView.extend({

    el: '#page',
    template_name: 'admin/admin',

    events: {
        "click #sidebar .dashboard a": "dashboard",
        "click #sidebar .counselors a": "counselors",
        "click #sidebar .customers a": "customers",
        "click #sidebar .transactions a": "transactions",
        "click #sidebar .payouts a": "payouts",
        "click #sidebar .payables a": "payables",
        "click .page-title .action-container .btn": "btn_click"
    },

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        return this;
    },

    dashboard: function (event) {
        app.router.navigate('dashboard', true);
        return false;
    },

    counselors: function (event) {
        app.router.navigate('counselors', true);
        return false;
    },

    customers: function (event) {
        app.router.navigate('customers', true);
        return false;
    },

    transactions: function (event) {
        app.router.navigate('transactions', true);
        return false;
    },

    payouts: function (event) {
        app.router.navigate('payouts', true);
        return false;
    },

    payables: function (event) {
        app.router.navigate('payables', true);
        return false;
    },

    set_page: function (title, active_class, btn_text, btn_callback) {
        this.$el.find('.page-title h1').text(title);
        this.$el.find('#sidebar li.active').removeClass('active');
        this.$el.find('#sidebar li.' + active_class).addClass('active');
        /* Empty out the header actions */
        this.$el.find('.page-title .action-container').empty();

        if (btn_text) {
            this.$el.find('.page-title .action-container').append(
                '<button class="btn btn-primary btn-med"><i class="glyphicon glyphicon-plus"></i> ' + btn_text + '</button>');
            this.btn_callback = btn_callback;
        }
    },

    btn_click: function (event) {
        if (this.btn_callback) {
            this.btn_callback(event);
        }
    }
});
'use strict';
app.admin.CounselorView = app.BaseFormView.extend({

    id: 'counselor',

    template_name: 'admin/counselor',

    events: {
        "click #delete_modal .btn-delete-confirm" : "delete_permanently",
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        var me = this;
        /* Set the user_type_id to be counselor for when we are adding counselors */
        this.model.set('user_type_id', app.user_type_counselor);
        app.BaseFormView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });

        app.template_cache.load(['admin/admin', 'admin/datatable'], function () {
            /* Kill the old view */
            if (me.dataTableView) {
                me.dataTableView.remove();
            }

            /* Create a new view */
            me.dataTableView = new app.admin.CustomerDataTableView({
                url: app.rest_root + 'admin/users/customers/'+me.model.get('id'),
                edit_url: 'customer/',
                cls: 'customers',
                columns: [
                    {
                        "title": "Last Name",
                        "mDataProp": "lastname"
                    },
                    {
                        "title": "First Name",
                        "mDataProp": "firstname"
                    },
                    {
                        "title": "Username",
                        "mDataProp": "username"
                    },
                    {
                        "title": "Created",
                        "mDataProp": "created"
                    },
                    {
                        "title": "Last Login",
                        "mDataProp": "last_login"
                    }
                ]
            });
            /* Fetch calls the render */
            app.router.renderView(me.dataTableView, me.$el.find('#counselor-customers'));
        });

        return this;
    },

    cancel: function (event) {
        app.router.navigate('counselors', true);
        return false;
    },

    delete_permanently : function(event) {
        var me = this;
        $.post( app.rest_root+'admin/users/delete', {
            uuid: this.model.id
        }, function(response) {
            app.success_message(response.message, me.alert_container);
            app.router.navigate('counselors', true);
            $('body').removeClass('modal-open');
        });
        return false;
    }
});
'use strict';
app.admin.CustomerView = app.BaseFormView.extend({

    id: 'customer',

    template_name: 'admin/customer',

    events: {
        "click #delete_modal .btn-delete-confirm" : "delete_permanently",
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save",  /* BaseFormView.save() */
        "click #assign_counselor_modal .btn-primary": "assign_counselor"
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });
        return this;
    },

    cancel: function (event) {
        app.router.navigate('customers', true);
        return false;
    },

    assign_counselor : function(event) {
        var me = this;

        var counselor_id = $("#counselor_id").val();
        $("#assign_counselor_modal").modal('hide');

        $(".assign_counselor").button('loading');

        if(counselor_id) {
            $.post( app.rest_root+'admin/users/user_counselor', {
                uuid: this.model.id,
                counselor_id: counselor_id
            }, function(response) {
                me.model = new app.User(response);
                me.render();
            });
        }

        return false;
    },

    delete_permanently : function(event) {
        var me = this;
        $.post( app.rest_root+'admin/users/delete', {
            uuid: this.model.id
        }, function(response) {
            app.success_message(response.message, me.alert_container);
            app.router.navigate('customers', true);
            $('body').removeClass('modal-open');
        });
        return false;
    }
});
'use strict';
app.admin.CustomerDataTableView = app.DataTableView.extend({

    template_name: 'admin/datatable-customers',
    counselor_id: 0,

    events: {
        'click .dataTable tr td': 'edit',
        'change #counselor_id' : 'set_counselor_id'
    },

    set_counselor_id : function(event) {
        this.counselor_id = $(event.currentTarget).val();
        this.table.fnDraw();
    },

    get_filter_params: function (aoData) {
        aoData.push({ "name":"counselor_id", "value": this.counselor_id });
    }
});
'use strict';
app.admin.DashboardView = app.BaseView.extend({

    id: 'dashboard',

    template_name: 'admin/dashboard',

    events: {
        "click button.submit" : "update_config"
    },
    update_config : function(event) {
        var attributes = {};
        var me = this;
        this.$el.find('.config-value').each(function() {
            var value = $(this).val();
            var key = $(this).attr('name');

            /* Handle checkbox fields */
            if($(this).attr('type')==='checkbox' && !$(this).is(":checked")) {
                if($(this).val()==1) {
                    value = 0;
                }
            }

            attributes[key] = value;
        });
        $(event.currentTarget).button('loading');

        $.post(app.rest_root+'admin/system_config/config', {config: attributes}, function(response) {
            $(event.currentTarget).button('reset');
            app.success_message('Successfully updated the system configuration', me.$el.find('.alert-success'));
        }, 'json');
        return false;
    }


});
'use strict';
app.admin.NotificationView = app.BaseFormView.extend({

    id: 'notification',

    template_name: 'admin/notification',

    events: {
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save",  /* BaseFormView.save() */
        "click .btn-test" : "test"
    },

    render: function () {
        var me = this;
        app.BaseFormView.prototype.render.call(this);
        setTimeout(function() {
            me.$el.find('#notification-body').show();
            tinymce.init({
                menubar: false,
                selector: 'textarea#notification-body',
                height:400,
                inline_styles : true
            });
        },500);

        return this;
    },

    cancel: function (event) {
        app.router.navigate('notifications', true);
        return false;
    },

    save : function(event, callback) {
        var attributes = {
            body: tinyMCE.activeEditor.getContent({format : 'raw'})
        };

        app.BaseFormView.prototype.save.call(this, event, this.model, callback, this.$el, attributes);
        return false;
    },

    test: function(event) {
        var me = this;
        this.save(event, function() {
            me.$el.find('.submit-container .alert').hide();
            $.ajax(app.rest_root + 'admin/notifications/test/', {
                type: 'POST',
                data: {
                    emails : me.$el.find('#test-emails').val()
                },
                dataType: 'json',
                success: function (response, textStatus, jqXHR) {
                    app.success_message(response.message, me.alert_container);
                    me.$el.find('#test_notification_modal').modal('hide');
                    //app.router.navigate('payouts', true);
                },
                error: function (jqXHR, textStatus, error) {
                    app.error_message($.parseJSON(jqXHR.responseText).message, me.alert_container);
                    me.$el.find('#test_notification_modal').modal('hide');
                    //submit_btn.button('reset');
                }
            });
        })
        return false;
    }
});
'use strict';
app.admin.PayableView = app.BaseView.extend({

    id: 'payable',

    template_name: 'admin/payable',

    events: {
        "click .submit-container button.back": "back",
        "click .submit-container button.pay": "pay"
    },

    initialize: function (options) {
        this.options = _.extend({
            remote: true,
            method: 'POST',
            perPage: 20,
            columns: [
                {
                    "title": "Transaction Type",
                    "mDataProp": "transaction_type"
                },
                {
                    "title": "Created",
                    "mDataProp": "created"
                },
                {
                    "title": "Customer",
                    "mDataProp": "customer"
                },
                {
                    "title": "Amount",
                    "mDataProp": "amount"
                }
            ],
            sorting: []
        }, options);
    },

    render: function () {
        this.model.set('columns',this.options.columns);
        app.BaseView.prototype.render.call(this);

        this.render_table();
        return this;
    },

    back: function (event) {
        app.router.navigate('payables', true);
        return false;
    },

    pay: function(event) {
        var submit_btn = this.$el.find('button.pay');
        submit_btn.button('loading');

        var me = this;
        $.ajax(app.rest_root + 'admin/payables/pay/'+this.model.get('uuid'), {
            type: 'POST',
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                app.success_message(response.message, me.alert_container);
                app.router.navigate('payouts', true);
            },
            error: function (jqXHR, textStatus, error) {
                app.error_message($.parseJSON(jqXHR.responseText).message, me.alert_container);
                submit_btn.button('reset');
            }
        });
    },

    render_table: function () {
        var me = this;

        this.table = this.$el.find('#datatable').dataTable({
            "sAjaxSource": app.rest_root + 'admin/payables/payable_items/'+this.model.get('uuid'),
            "sServerMethod": this.options.method,
            "bServerSide": this.options.remote,
            "iDisplayLength": this.options.perPage,
            "aoColumns": this.options.columns,
            "aaSorting": this.options.sorting
        });
    }
});
'use strict';
app.admin.PayoutView = app.BaseView.extend({

    id: 'payout',

    template_name: 'admin/payout',

    events: {
        "click .submit-container button.back": "back",
        "click .submit-container button.pay": "pay"
    },

    initialize: function (options) {
        this.options = _.extend({
            remote: true,
            method: 'POST',
            perPage: 20,
            columns: [
                {
                    "title": "Transaction Type",
                    "mDataProp": "transaction_type"
                },
                {
                    "title": "Created",
                    "mDataProp": "created"
                },
                {
                    "title": "Customer",
                    "mDataProp": "customer"
                },
                {
                    "title": "Amount",
                    "mDataProp": "amount"
                }
            ],
            sorting: []
        }, options);
    },

    render: function () {
        this.model.set('columns',this.options.columns);
        app.BaseView.prototype.render.call(this);

        this.render_table();
        return this;
    },

    back: function (event) {
        app.router.navigate('payouts', true);
        return false;
    },

    render_table: function () {
        var me = this;

        this.table = this.$el.find('#datatable').dataTable({
            "sAjaxSource": app.rest_root + 'admin/payouts/payable_items/'+this.model.get('uuid'),
            "sServerMethod": this.options.method,
            "bServerSide": this.options.remote,
            "iDisplayLength": this.options.perPage,
            "aoColumns": this.options.columns,
            "aaSorting": this.options.sorting
        });
    }
});
'use strict';
app.admin.PricingView = app.BaseFormView.extend({

    id: 'pricing',

    template_name: 'admin/pricing',

    events: {
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        var me = this;
        app.BaseFormView.prototype.render.call(this);

        return this;
    },

    save: function (event) {
        var me = this;
        app.BaseFormView.prototype.save.call(this, event, this.model, function () {
            me.$el.find('input.money').each(function () {
                $(this).val(accounting.formatMoney($(this).val()));
            })
        });
        return false;
    }
});
'use strict';
app.admin.TransactionView = app.BaseView.extend({

    id: 'transaction',

    template_name: 'admin/transaction',

    events: {
        "click .submit-container button.back": "back",
        "click .customer": "customer",
        "click .counselor": "counselor"
    },

    render: function () {
        app.BaseView.prototype.render.call(this);
        return this;
    },

    back: function (event) {
        app.router.navigate('transactions', true);
        return false;
    },

    customer: function (event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        app.router.navigate('customer/' + uuid, true);
        return false;
    },

    counselor: function (event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        app.router.navigate('counselor/' + uuid, true);
        return false;
    }
});
'use strict';
app.CalendarView = app.BaseView.extend({

    template_name: 'dashboard/calendar',

    event_template: '<div data-uuid="<%=uuid%>" class="event">' +
                        '<div class="title"><%=title%></div>' +
                    '</div>',

    events: {
        'click .diary': 'open_diary'
    },

    initialize: function (options) {
        app.BaseView.prototype.initialize.call(this, options);
    },

    render: function () {
        app.BaseView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.calendar').addClass('active');

        return this;
    },

    build_calendar: function () {
        var me = this;

        this.$el.find("#main-calendar").fullCalendar({
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            events: app.rest_root + 'events/',
            eventRender : function(ev, element, view) {
                $(element).attr('data-uuid', ev.uuid).html(_.template(me.event_template, ev));
            }
        });
    }
});
'use strict';
app.ConversationListView = app.BaseListView.extend({
    id: 'conversations',

    messageListCollection : undefined,
    messageListView : undefined,

    template_name: 'dashboard/conversations',

    events: {
        'click .conversation' : 'conversation',
        'change #conversation-selector' : 'select_conversation'
    },

    render: function (collection) {
        app.BaseListView.prototype.render.call(this, collection);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.messages').addClass('active');

        /* When this is rendered, the conversation uuid is probably not set so we will just pull the first one
        off of the collection
         */
        if(!this.getConversationUuid()) {
            var firstConversation = _.first(collection.models);
            if(firstConversation) {
                this.setConversationUuid(firstConversation.get('uuid'));
            }
        }

        this.renderMessageList(this.getConversationUuid());

        this.$el.find('.conversation:first').addClass('active');

        return this;
    },

    conversation: function(event) {
        this.$el.find('.conversation').removeClass('active');
        this.setConversationUuid($(event.currentTarget).attr('data-id'));
        this.renderMessageList(this.getConversationUuid());

        $(event.currentTarget).addClass('active');
        return false;
    },

    select_conversation: function(event) {
        this.setConversationUuid($(event.currentTarget).val());
        this.renderMessageList(this.getConversationUuid());
        return false;
    },

    setConversationUuid : function(uuid) {
        this.options.conversation_uuid = uuid;
    },

    getConversationUuid : function() {
        return this.options.conversation_uuid;
    },

    renderMessageList: function(uuid) {
        var me = this;
        if(!this.messageListCollection) {
            this.messageListCollection = new app.MessageListCollection();
        }

        this.messageListCollection.uuid = uuid;
        this.messageListCollection.fetch({
            success : function(collection, response, options) {
                /* Kill the old view */
                if (me.messageListView) {
                    me.messageListView.remove();
                }
                me.messageListView = new app.MessageListView({
                    conversation_uuid : me.getConversationUuid()
                });

                app.router.renderCollectionView(me.messageListView, collection, '#message-list');
            }
        });
    }
});
'use strict';
app.CreditsView = app.BaseView.extend({
    events: {
        'click .input-group-addon': 'toggle_count',
        'click button.submit': 'submit'
    },

    template_name: 'dashboard/credits',

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);
        this.bind('ok', this.confirm);
    },

    render: function () {
        app.BaseView.prototype.render.call(this);
        return this;
    },

    _get_diary_amount: function () {
        return Math.max(parseInt(this.$el.find('.diary-count input').val()), 0);
    },

    _get_counseling_amount: function () {
        return Math.max(parseInt(this.$el.find('.counseling-count input').val()), 0);
    },

    _get_total: function () {
        var diary_amount = this._get_diary_amount() * app.data.price_diary;
        var counseling_amount = this._get_counseling_amount() * app.data.price_counseling;

        return Math.max(diary_amount + counseling_amount, 0);
    },

    toggle_count: function (event) {
        this.$el.find('.no-selection-error:visible').hide();
        var input = $(event.currentTarget).siblings('input')
        if ($(event.currentTarget).hasClass('plus')) {
            input.val(parseInt(input.val()) + 1);
        } else {
            if (parseInt(input.val()) > 0) {
                input.val(parseInt(input.val()) - 1);
            }
        }

        this.$el.find('.header .total').text('Total: ' + accounting.formatMoney(this._get_total()));
    },

    confirm: function (modal) {
        this.modal = modal;
        if (this._get_total() <= 0) {
            this.$el.find('.no-selection-error').show();
            return false;
        }

        this.$el.find('#add-credits-form').hide();
        this.$el.find('#add-credits-confirm').show();
        modal.$el.find('.modal-footer').hide();
    },

    submit: function () {
        var me = this;
        var submit_btn = this.$el.find('button.submit');
        submit_btn.button('loading');

        $.ajax(app.rest_root + 'users/purchase', {
            type: 'POST',
            data: {
                diary_cnt: this._get_diary_amount(),
                counseling_cnt: this._get_counseling_amount()
            },
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                if (me.options.dashboard) {
                    me.options.dashboard.trigger('addCredits', response.data.credits);
                }

                if (me.modal)
                    me.modal.close();
            },
            error: function (jqXHR, textStats, error) {
                var alert = me.$el.find('.alert-danger');
                alert.html('<strong>Error: </strong> There was an error with your submission.  Please try again').show();
                submit_btn.button('reset');
            }
        });

    }
});
'use strict';
app.CustomerView = app.BaseFormView.extend({
    id: 'customer',
    template_name: 'dashboard/customer',

    noteListCollection: undefined,
    noteListView: undefined,

    diaryListCollection: undefined,
    diaryListView: undefined,

    events: {
        'click .dropper' : 'open_menu',
        'click .dropper-menu li' : 'menu_choice'
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.customers').addClass('active');

        if(this.options.path==='diaries') {
            this.renderDiaryList();
        } else if(this.options.path==='notes') {
            this.renderNotesList();
        } else {
            this.renderInfo();
        }
        return this;
    },

    open_menu : function() {
        this.$el.find('.dropper-menu').show();
        return false;
    },

    menu_choice : function(event) {
        this.$el.find('.dropper-menu li').removeClass('active');
        if($(event.currentTarget).hasClass('notes')) {
            this.renderNotesList();
            app.router.navigate('customer/' + this.model.get('uuid') + '/notes');
        } else if($(event.currentTarget).hasClass('diary')) {
            this.renderDiaryList();
            app.router.navigate('customer/' + this.model.get('uuid') + '/diaries');
        } else if($(event.currentTarget).hasClass('info')) {
            this.renderInfo();
            app.router.navigate('customer/' + this.model.get('uuid') + '/info');
        }
        this.$el.find('.dropper-menu').hide();
    },

    renderNotesList: function () {

        this.$el.find('.dropper .icon').hide();
        this.$el.find('.dropper-menu li.notes').addClass('active');
        this.$el.find('.dropper .icon-notes').css('display','block');

        var me = this;
        if (!this.noteListCollection) {
            this.noteListCollection = new app.NoteListCollection();
        }

        this.noteListCollection.uuid = this.model.get('uuid');
        this.noteListCollection.fetch({
            success: function (collection, response, options) {
                /* Kill the old view */
                if (me.noteListView) {
                    me.noteListView.remove();
                }
                me.noteListView = new app.NoteListView({
                    user_uuid : me.model.get('uuid')
                });

                app.router.renderCollectionView(me.noteListView, collection, '#item-list');
            }
        });
    },

    renderDiaryList: function () {

        this.$el.find('.dropper .icon').hide();
        this.$el.find('.dropper-menu li.diary').addClass('active');
        this.$el.find('.dropper .icon-diary').css('display','block');

        var me = this;
        if (!this.diaryListCollection) {
            this.diaryListCollection = new app.DiaryListCollection();
        }

        this.diaryListCollection.uuid = this.model.get('uuid');
        this.diaryListCollection.fetch({
            success: function (collection, response, options) {
                /* Kill the old view */
                if (me.diaryListView) {
                    me.diaryListView.remove();
                }
                me.diaryListView = new app.DiaryListCounselorView({
                    user_uuid : me.model.get('uuid')
                });

                app.router.renderCollectionView(me.diaryListView, collection, '#item-list');
            }
        });
    },

    renderInfo: function () {

        this.$el.find('.dropper .icon').hide();
        this.$el.find('.dropper-menu li.info').addClass('active');
        this.$el.find('.dropper .icon-info').css('display','block');

        var me = this;
        if (me.infoView) {
            me.infoView.remove();
        }
        me.infoView = new app.InfoView({
            model : me.model
        });

        app.router.renderView(me.infoView, '#item-list', function() {
        });

    }
});
'use strict';
app.CustomerListView = app.BaseListView.extend({
    id: 'customers',
    template_name: 'dashboard/customers',

    events: {
        'click .customer': 'customer'
    },

    render: function (collection) {
        app.BaseListView.prototype.render.call(this, collection);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.customers').addClass('active');

        return this;
    },

    customer: function (event) {
        var id = $(event.currentTarget).attr('data-id');
        app.router.navigate('customer/' + id + '/info', true);
    }
});
'use strict';
app.DashboardCounselorView = app.BaseView.extend({

    el: '#backbone-container',
    template_name: 'dashboard/dashboard',

    events: {
        "click #sidebar .nav .calendar": "calendar",
        "click #sidebar .nav .messages": "conversations",
        "click #sidebar .nav .customers": "customers",
        "click button.start-session" : "start_video_chat"
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        this.upcoming_event();

        return this;
    },

    calendar: function (event) {
        app.router.navigate('calendar', true);
        return false;
    },

    conversations: function (event) {
        app.router.navigate('conversations', true);
        return false;
    },

    customers: function (event) {
        app.router.navigate('customers', true);
        return false;
    },

    /* See if the user has an upcoming event */
    upcoming_event: function(event) {
        var me = this;
        app.fetch_upcoming_event(me);
        setTimeout(function() {
            me.upcoming_event();
        }, 60000);
    },

    start_video_chat : function(event) {
        var cache_buster = new Date().getTime();
        window.open(app.app_root+'chats/event/'+$(event.currentTarget).attr('data-id')+'/'+cache_buster, 'Blush Video Session', 'height=600,width=800,status=no,toolbar=yes,titlebar=no');
        return false;
    }

});
'use strict';
app.DashboardCustomerView = app.BaseView.extend({

    el: '#backbone-container',
    template_name: 'dashboard/dashboard',

    events: {
        "click .user-meta .messages a": "messages",
        "click .user-meta .diaries a": "diaries",
        "click #btn-add-diary": "diary",
        "click #btn-add-video": "add_video",
        "click #btn-add-credits": "add_credits",
        "click .fc-event" : "event",
        "click button.start-session" : "start_video_chat"
    },

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);
        this.bind('addCredits', this.add_credits_complete);
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        this.calendar();

        this.upcoming_event();

        this.welcome();

        return this;
    },

    messages: function (event) {
        app.router.navigate('messages', true);
        return false;
    },

    event: function(event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        app.router.navigate('event/'+uuid, true);
        return false;
    },

    diary: function (event) {
        if(parseInt(app.user.credits)<app.data.credits_diary) {
            app.error_message(app.msg_error.credits_invalid_diary, this.alert_container);
        } else if(!app.user.counselor.uuid) {
            app.error_message(app.msg_error.no_counselor, this.alert_container);
        } else {
            app.router.navigate('diary', true);
        }
        return false;
    },

    add_video: function (event) {
        if(parseInt(app.user.credits)<app.data.credits_counseling) {
            app.error_message(app.msg_error.credits_invalid_video, this.alert_container);
        } else if(!app.user.counselor.uuid) {
            app.error_message(app.msg_error.no_counselor, this.alert_container);
        } else {
            app.router.navigate('add-video', true);
        }
        return false;
    },

    diaries: function (event) {
        app.router.navigate('diaries', true);
        return false;
    },

    add_credits: function (event) {
        var me = this;
        app.template_cache.load(['dashboard/credits'], function () {
            /* Kill the old view */
            if (me.modalView) {
                me.modalView.remove();
            }
            /* Create a new view */
            me.creditsView = new app.CreditsView({
                dashboard: me,
                model: me.model
            });
            me.modalView = new Backbone.BootstrapModal({
                content: me.creditsView,
                modalClass: 'add-credits',
                allowOk: me.model.get('stripe_customer_id'),
                okText: 'SUBMIT',
                okCloses: false
            }).open();
        });
        return false;
    },

    welcome : function () {
        var me = this;
        if(!app.user.previous_login) {
            $.get(app.rest_root+'users/welcome', {}, function(data) {
                if(data) {
                    me.modalView = new Backbone.BootstrapModal({
                        content: data,
                        title: 'Welcome!',
                        modalClass: 'welcome',
                        allowOk: true,
                        allowCancel: false
                    }).open();
                }
            });
        }
    },

    /**
     * The user has completed purchasing their credits
     * @param total_credits - the total number of credits for a user
     */
    add_credits_complete: function (total_credits) {
        app.success_message(app.msg_success.credits_purchase, this.alert_container);
        this.$el.find('div.credits strong').text(total_credits);
    },

    /* Initialize the calendar in the sidebar */
    calendar: function () {
        $("#sidebar-calendar").fullCalendar({
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            events: app.rest_root + 'events/',
            eventRender : function(ev, element, view) {
                $(element).attr('data-uuid', ev.uuid).html('<div class="fc-day-number">'+ev.day+'</div>');
            }
        })
    },

    /* See if the user has an upcoming event */
    upcoming_event: function() {
        app.fetch_upcoming_event(this);
    },

    start_video_chat : function(event) {
        var cache_buster = new Date().getTime();
        window.open(app.app_root+'chats/event/'+$(event.currentTarget).attr('data-id')+'/'+cache_buster, 'Blush Video Session', 'height=600,width=800,status=no,toolbar=yes,titlebar=no');
        return false;
    }

});
'use strict';
app.DiaryView = app.BaseFormView.extend({
    id: 'diary',
    className: 'diary',
    template_name: 'dashboard/diary',

    MAX_WORD_COUNT: 350,

    events: {
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "submit",  /* BaseFormView.save() */
        "click .submit-container button.save": "save_draft"  /* BaseFormView.save() */
        //"keyup textarea.text" : "count_words"
    },

    render: function () {
        console.log(this.model);
        app.BaseFormView.prototype.render.call(this);
        return this;
    },

    cancel: function (event) {
        app.router.navigate('diaries', true);
        return false;
    },


    /**
     * Save the journal as a draft and don't send it to the counselor
     * @param event
     * @return {boolean}
     */
    save_draft: function (event) {
        this.model.set('draft', 1);
        app.BaseFormView.prototype.save.call(this, event, undefined, function(response) {});
        return false;
    },



    submit: function (event) {
        var me = this;
        // Ensure that the journal isn't a draft.
        this.model.set('draft', 0);
        app.BaseFormView.prototype.save.call(this, event, undefined, function(response) {
            /* subtract the credits from the user if the response has a uuid since it was a new diary*/
            if(response.data.uuid) {
                app.user.credits = app.user.credits - app.data.credits_diary;
                $("#sidebar .credits strong").text(app.user.credits);
                me.$el.find('button.save, button.submit').hide();
                me.$el.find('button.cancel').text('Close');
            }
        });
        return false;
    },

    count_words: function(event) {
        var words = $(event.currentTarget).val().trim().split(" ");
        var word_count = words.length;

        var word_count_container = this.$el.find('.word-count');

        if(word_count > this.MAX_WORD_COUNT) {
            this.$el.find('button.submit').attr('disabled', 'disabled');
            word_count_container.addClass('invalid');
        } else {
            this.$el.find('button.submit').removeAttr('disabled');
            word_count_container.removeClass('invalid');
        }
        word_count_container.text(this.MAX_WORD_COUNT - word_count);
    }
});
'use strict';
app.DiaryListCounselorView = app.BaseListView.extend({
    id: 'diaries',

    template_name: 'dashboard/diaries-counselor',

    events: {
        'click .diary-counselor .title' : 'toggle_diary',
        'click .comments button.submit' : 'save_comments'
    },

    /**
     * Show the body of the diary to the counselor and if it is un-read, mark it as read
     * @param event
     */
    toggle_diary: function (event) {
        var body = $(event.currentTarget).parent().find('.body-comments');
        body.toggle();
        if(body.is(":visible")) {
            /* If the counselor is viewing this for the first time, mark it as read */
            var parent = $(event.currentTarget).closest('.diary-counselor');
            var isRead = parent.attr('data-read');
            if(parseInt(isRead)<1) {
                var diary = new app.Diary({uuid: parent.attr('data-uuid'), read: 1, draft: 0});
                diary.save();
                parent.attr('data-read', 1);
            }
        }
    },

    /**
     * Fired when the counselor clicks the save button on the comments form.  Updates the comments attached to the form.
     * @param event
     * @returns {boolean}
     */
    save_comments : function(event) {
        var uuid = $(event.currentTarget).attr('data-uuid');
        this.save(event, new app.Diary({uuid: uuid, draft: 0}), undefined, $(event.currentTarget).closest('.body-comments'));
        return false;
    }
});
'use strict';
app.DiaryListCustomerView = app.BaseListView.extend({
    id: 'diaries',

    template_name: 'dashboard/diaries',

    events: {
        'click .diary': 'open_diary'
    },

    open_diary: function (event) {
        var id = $(event.currentTarget).attr('data-id');
        app.router.navigate('diary/' + id, true);
    }
});
'use strict';
app.EventView = app.BaseView.extend({
    id: 'event',
    className: 'event col-lg-12',
    template_name: 'dashboard/event',

    events: {
        "click .submit-container button.cancel": "cancel"
    },

    cancel: function (event) {
        app.router.navigate('events', true);
        return false;
    }
});
'use strict';
app.EventListView = app.BaseListView.extend({
    id: 'events',

    template_name: 'dashboard/events',

    events: {
        'click button.cancel' : 'cancel_event'
    },

    cancel_event : function(event) {
        var me = this;
        $(event.currentTarget).closest('.event').find('.alert-danger').empty().hide();
        var submit_button = $(event.currentTarget);
        submit_button.button('loading');

        var uuid = $(event.currentTarget).attr('data-uuid');
        $.ajax(app.rest_root + 'events/cancel', {
            type: 'POST',
            data: {
                uuid: uuid
            },
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                submit_button.button('reset');
                $(event.currentTarget).closest('.event').remove();
                app.success_message(response.message, me.$el.find('.alert-container'));
                app.user.credits = response.data.user.credits;
                app.user.pending_credits = response.data.user.pending_credits;
                $('#sidebar .credits strong').text(app.user.credits - app.user.pending_credits);
            },
            error: function (jqXHR, textStats, error) {
                submit_button.button('reset');
                var message = $.parseJSON(jqXHR.responseText).message;
                var alert = $(event.currentTarget).closest('.event').find('.alert-danger');
                alert.html('<strong>Error: </strong> '+message).show();
                submit_btn.button('reset');
            }
        });
        return false;
    }
});
'use strict';
app.InfoView = app.BaseView.extend({
    id: 'info',
    template_name: 'dashboard/info'
});
'use strict';
app.MessageView = app.BaseFormView.extend({
    id: 'message',
    template_name: 'dashboard/message',
    cancel_url :  app.get_router() ? app.get_router().root_url + 'messages/' : app.root_url + 'messages/',

    events: {
        "click .submit-container button.cancel": "cancel",
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        return this;
    },

    cancel: function (event) {
        app.router.navigate(this.cancel_url, true);
        return false;
    }
});
'use strict';
app.MessageListView = app.BaseListView.extend({
    id: 'messages',

    template_name: 'dashboard/messages',

    events: {
        'click .new-message': 'message',
        'click .submit-container .submit': 'reply'
    },

    message: function (event) {
        /* If this is a regular user and they don't have a coach yet, don't let them do anything */

        if((app.user.user_type_id == app.user_type_customer) && !app.user.counselor.uuid) {
            app.error_message(app.msg_error.no_counselor, this.alert_container);
        }  else {
            app.router.navigate('message', true);
        }
        return false;
    },

    reply: function (event) {
        /* Create a new message, send it, and fetch the list */
        var me = this;
        var message = new app.Message();

        var attributes = this.$el.find('form').serializeObject();
        attributes.conversation_uuid = this.options.conversation_uuid;

        var submit_button = this.$el.find('.submit-container button.submit');
        submit_button.button('loading');

        this.$el.find('.submit-container .alert').hide();

        message.save(attributes, {
            success: function (model, response, options) {
                if (response && response.message) {
                    var alert = me.$el.find('.submit-container .alert-success');
                    alert.html('<strong>Success: </strong> ' + response.message).show();
                    alert.delay(3000).fadeOut();
                    me.collection.fetch({
                        success: function (collection) {
                            me.render(collection);

                            /* Scroll down to the reply field */
                            var targetOffset = me.$el.find('.reply').offset().top;
                            $('html,body').animate({scrollTop: targetOffset}, 300);
                        }
                    });

                }
                submit_button.button('reset');
            },
            error: function (model, xhr, options) {
                var alert = me.$el.find('.submit-container .alert-danger');
                alert.html('<strong>Error: </strong> There was an error with your submission.  Please try again').show();
                submit_button.button('reset');
            }

        });
        return false;
    }
});
'use strict';
app.NoteListView = app.BaseListView.extend({
    id: 'notes',
    template_name: 'dashboard/notes',
    user_uuid : undefined,

    events: {
        'click .submit-container .btn-primary' : 'add_note'
    },

    render: function (collection) {
        app.BaseListView.prototype.render.call(this, collection);
        return this;
    },

    add_note : function(event) {
        event.stopPropagation();

        var note = new app.Note();
        note.urlRoot = note.urlRoot + '/' + this.options.user_uuid;
        this.save(event, note);
        return false;
    }
});
'use strict';
app.VideoAddView = app.BaseFormView.extend({
    id: 'video_add',
    template_name: 'dashboard/videoadd',
    template_availability_name: 'dashboard/includes/videoadd-availability',


    events: {
        "change #session_date" : "update_timeslots",
        "click #video-add-submit": "save"  /* BaseFormView.save() */
    },

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);

        /** Set the pricing value to be the actual json of the object **/
        this.options.availability = this.options.availability.toJSON();
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });
        return this;
    },

    /**
     * Validates that the user cannot schedule a video session within 24 hours of current time
     * and that they aren't picking a time that their coach isn't available.
     */
    validate : function() {
        var minDate = app.calculate_24hours();

        var date = this.$el.find('#session_date').val();
        var time = this.$el.find('#session_time').val();

        var dateTime = app.date_from_input(date, time);
        if(minDate.getTime() > dateTime.getTime() && app.data.prevent_schedule_24hour) {
            app.error_message('Video sessions cannot be schedule less than 24 hours in advance.', this.$el.find('.alert-holder'));
            return false;
        }
        return true;
    },

    /**
     * Pull any custom timeslots from the calendar for the specified date
     * @param event
     */
    update_timeslots: function(event) {
        var me = this,
            val = $(event.currentTarget).val();
        $.ajax({
            url: app.rest_root+'events/timeslots',
            method: 'POST',
            data: {
                session_date: val
            },
            success: function(response) {
                var html = _.template(app.template_cache.get(me.template_availability_name), {
                    model: response,
                    session_date: val
                });
                me.$el.find('.additional-availability').html(html);
            }
        });
    },

    save: function (event) {
        if (this.$el.find('form').valid() && this.validate()) {
            var submit_button = $(event.currentTarget);
            submit_button.button('loading');

            var attributes = this.$el.find('form').serializeObject();
            var me = this;
            $.ajax({
                url: app.rest_root+'events/video_add',
                method: 'POST',
                data: attributes,
                success: function(response) {
                    submit_button.button('reset');
                    app.router.navigate('events', true);
                    app.success_message('Your video session has been scheduled successfully', $("#dashboard-alerts"));
                    app.user.credits = response.data.user.credits;
                    app.user.pending_credits = response.data.user.pending_credits;
                    $('#sidebar .credits strong').text(app.user.credits - app.user.pending_credits);
                },
                error: function(xhr, status, error) {
                    submit_button.button('reset');
                    var response = $.parseJSON(xhr.responseText);
                    if(response.message) {
                        app.error_message(response.message, me.$el.find('.alert-holder'))
                    }
                }
            });
        }
        return false;
    }
});
'use strict';
app.AccountTypeView = app.BaseFormView.extend({
    template_name: 'my_account/account_type',

    events: {
        "click .submit-container button.submit": "save",  /* BaseFormView.save() */
        "click .plan-item .choose" : "choose_plan",
        "click .plan-cancel" : "cancel_plan"
    },

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);

        /** Set the pricing value to be the actual json of the object **/
        this.options.pricing = this.options.pricing.toJSON();
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.account_type').addClass('active');

        return this;
    },

    choose_plan : function(event) {
        this.$el.find('.item-container.current').removeClass('current');
        $(event.currentTarget).closest('.item-container').addClass('current');
        this.model.set('plan_id', $(event.currentTarget).attr('data-id'));
        return false;
    },

    cancel_plan : function(event) {
        var me = this;
        app.get_router().confirm('Cancel Subscription?', 'Are you sure you want to cancel your subscription?', function () {
            me.model.set('plan_id', -1);
            me.save(null, null, function() {
                app.success_message('Your subscription has been cancelled successfully!  Thank you for your business!');
                me.$el.find('.item-container.current').removeClass('current');
                me.$el.find('.plan-cancel').hide();
            });
        });
        return false;
    }

});
'use strict';
app.AvailabilityView = app.BaseFormView.extend({
    template_name: 'my_account/availability',

    events: {
        "click .submit-container button.add": "add", /* BaseFormView.save() */
        "click .remove": "remove"
    },

    add_template: '<tr data-id="<%=id%>">' +
        '<td><%=day_of_week%></td>' +
        '<td><%=pretty_start_time%></td>' +
        '<td><%=pretty_end_time%></td>' +
        '<td><button class="btn btn-xs pull-right btn-danger remove">Delete</button></td>' +
        '</tr>',

    initialize: function (options) {
        app.BaseFormView.prototype.initialize.call(this, options);

        /** Set the pricing value to be the actual json of the object **/
        this.options.availability = this.options.availability.toJSON();
    },

    render: function () {
        var me = this;
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.availability').addClass('active');
        /* Create a new view of the availiability calendar*/

        setTimeout(function () {

            me.calendarView = new app.AvailabilityCalendarView({
                model: new app.AvailabilityCalendar()
            });
            app.get_router().renderView(me.calendarView, '#availability-calendar-container', function () {
                me.calendarView.build_calendar();
            });
        }, 500);

        setTimeout(function() {
            me.$el.find('#calendar').removeClass('active');
        },1000);


        return this;
    },

    remove: function (event) {
        var me = this;
        if (event) {
            var row = $(event.currentTarget).closest('tr');
            var id = row.attr('data-id');
            var data = {
                action: 'remove',
                id: id
            }

            $.ajax(app.rest_root + 'users/availability', {
                dataType: 'json',
                data: data,
                type: 'POST',
                success: function (response) {
                    app.success_message('Your availability day has been successfully removed.', me.$el.find('.alert-container'));
                    row.remove();
                },
                error: function () {
                    me.$el.find('.alert-danger').show().html('<p>There was an error while saving your entry, please try again.</p>');
                }
            });
        }
    },

    add: function (event) {
        var me = this;

        this.$el.find('.alert-danger').hide().empty();
        if (this.$el.find('form').valid()) {
            var data = {
                action: 'add',
                day: this.$el.find('#day').val(),
                start_time: this.$el.find('#start_time').val(),
                end_time: this.$el.find('#end_time').val()
            }

            if (data.start_time >= data.end_time) {
                this.$el.find('.alert-danger').show().html('<p>Start Time should be before End Time.</p>');
            } else {
                $.ajax(app.rest_root + 'users/availability', {
                    dataType: 'json',
                    data: data,
                    type: 'POST',
                    success: function (response) {
                        app.success_message('Your availability day has been successfully added.', me.$el.find('.alert-container'));
                        me.$el.find('table').append(_.template(me.add_template, response));
                    },
                    error: function () {
                        me.$el.find('.alert-danger').show().html('<p>There was an error while saving your entry, please try again.</p>');
                    }
                });
            }
        }

        return false;
    }

});
'use strict';
app.AvailabilityCalendarView = app.BaseView.extend({

    template_name: 'my_account/includes/availability-calendar-calendar',
    event_template: '<div data-id="<%=id%>" class="availability <%=cls%>">' +
        '<div class="title"><%=title%></div>' +
        '<button class="calendar-remove"><i class="fa fa-times text-danger"></i></button>' +
        '</div>',

    events: {
        'click .fc-day .fc-day-number' : 'show_modal',
        'click .btn-save-availability-calendar' : 'save',
        'click .calendar-remove' : 'destroy',
        'change .is_all_day' : 'change_is_all_day'
    },

    initialize: function (options) {
        app.BaseView.prototype.initialize.call(this, options);
        this.on('save_complete', this.after_save);
    },

    render: function () {
        app.BaseView.prototype.render.call(this);

        return this;
    },

    destroy: function(event) {
        var id = $(event.currentTarget).closest('.availability').data('id');
        this.model = new app.AvailabilityCalendar({id: id});
        app.BaseView.prototype.destroy.call(this, event);
    },

    change_is_all_day: function(event) {
        var is_checked = $(event.currentTarget).prop('checked');
        if(is_checked) {
            this.$el.find('.form-group-date').hide();
            this.$el.find('.is_all_day_val').val(1);
            this.$el.find('.form-group-date input').data('rule-required', false);
        } else {
            this.$el.find('.form-group-date').show();
            this.$el.find('.is_all_day_val').val('');
            this.$el.find('.form-group-date input').data('rule-required', true);
        }
    },

    after_save: function(event) {
        $('#availability-calendar-modal').modal('hide');
        this.$el.find("#availability-calendar").fullCalendar('refetchEvents');
    },

    show_modal: function(event) {
        var date = $(event.currentTarget).closest('.fc-day').data('date');
        this.model = new app.AvailabilityCalendar();

        this.$el.find('#availability-calendar-modal input,#availability-calendar-modal select').val('');
        this.$el.find('#availability-calendar-modal input').prop('checked', '');
        this.$el.find('.form-group-date').show();
        this.$el.find('.is_all_day_val').val('');
        this.$el.find('#availability-calendar-modal input[name="date"]').val(date);
        this.$el.find('#availability-calendar-modal').modal('show');
    },

    build_calendar: function () {
        var me = this;

        this.$el.find("#availability-calendar").fullCalendar({
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            contentHeight: 1000,
            events: app.rest_root + 'users/availability_calendar/',
            eventRender : function(ev, element, view) {
                $(element).attr('data-uuid', ev.uuid).html(_.template(me.event_template, ev));
            },
            eventAfterAllRender: function(view) {
                $('.fc-day .fc-day-number').each(function() {
                    if($(this).find('.fa').length<1) {
                        $(this).append('<i class="fa fa-plus-circle"></i>');
                    }
                });
            }
        });
    }
});
'use strict';
app.NotificationsView = app.BaseFormView.extend({
    template_name: 'my_account/notifications',

    events: {
        "click .submit-container button.submit": "save"  /* BaseFormView.save() */
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.notifications').addClass('active');

        return this;
    }

});
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
'use strict';
app.PaymentView = app.BaseFormView.extend({
    template_name: 'my_account/payment',

    events: {
        "click .submit-container button.submit": "save",  /* PaymentView.save() */
        "click .card-fields-toggle" : "toggle_card_fields"
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.payment').addClass('active');

        return this;
    },

    save: function (event) {
        if (this.$el.find('form').valid()) {

            var submit_button = this.$el.find('.submit-container button.submit');
            submit_button.button('loading');
            this.$el.find('.submit-container .alert').hide();

            if($('.card-number').length>0) {
                /* Allow them to save without a card so they can update their location */
                var number = $('.card-number').val();
                if(number) {
                    this.save_card(submit_button);
                } else {
                    this.save_success(null, 'credit');
                }
            } else {
                /* Allow them to save without a card so they can update their location */
                var number = $('.account-number').val();
                if(number) {
                    this.save_checking(submit_button);
                } else {
                    this.save_success(null, 'checking');
                }
            }
        }
        return false;
    },

    save_card : function(submit_button) {
        var me = this;
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val(),
            name: $(".cardholder-name").val()
        }, function (status, response) {

            if (response.error) {
                var alert = me.$el.find('.submit-container .alert-danger');
                alert.show().text(response.error.message);
                submit_button.button('reset');
            } else {
                me.save_success(response, 'credit');
            }
        });
    },

    save_checking : function(submit_button) {
        var me = this;
        Stripe.bankAccount.createToken({
            country:'US',
            routingNumber:$('.routing-number').val(),
            accountNumber:$('.account-number').val()
        }, function (status, response) {

            if (response.error) {
                var alert = me.$el.find('.submit-container .alert-danger');
                alert.show().text(response.error.message);
                submit_button.button('reset');
            } else {
                me.save_success(response, 'checking');
            }
        });
    },

    /**
     * Fired when the stripe call completes to save the form
     * @param response
     * @param account_type
     */
    save_success : function(response, account_type) {
        /* Call the save method on baseForm */
        var form$ = this.$el.find("#payment-form");
        if(response) {
            var token = response['id'];
            form$.append("<input type='hidden' name='stripe_token' value='" + token + "' />");
        }
        this.model.set('account_type', account_type);

        app.BaseFormView.prototype.save.call(this);
        this.model.set('stripe_token', '');
    },

    toggle_card_fields : function(event) {
        this.$el.find('.card-fields').toggle();
        return false;
    }

});
'use strict';
app.ProfileView = app.BaseFormView.extend({

    template_name: 'my_account/profile',

    registration : undefined, /* The registration model */

    events: {
        "click .registration .submit-container button.save": "save_registration",  /* BaseFormView.save() */
        "click .submit-container button.submit": "save",  /* BaseFormView.save() */
        "click .picture-upload-link" : "choose_picture",  /* BaseFormView.save() */
        "change #picture-upload-input" : "submit_picture",
        'change .yes-no-more input': 'toggle_more_text'
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);
        var me = this;

        /* Set the active nav item */
        $('.nav li a').removeClass('active');
        $('.nav li a.profile').addClass('active');
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });

        this.$el.find('.choices input').each(function (input) {
            var name = $(this).attr('name');
            var value = $(this).attr('value');
            if (me.registration.get(name) === value) {
                $(this).parent().button('toggle');
            }
        });

        return this;
    },

    choose_picture : function(event) {
        this.$el.find("#picture-upload-input").click();
        return false;
    },

    toggle_more_text: function (event) {
        var value = $(event.currentTarget).val();
        var more_field = this.$el.find('div.' + $(event.currentTarget).attr('name'));
        if (value === 'Yes') {
            more_field.show();
        } else {
            more_field.hide();
        }
    },

    save_registration : function(event) {
        var form = $(event.currentTarget).closest('form');
        if (form.valid()) {
            var attributes = form.serializeObject();
            var me = this;

            var submit_button = $(event.currentTarget);
            submit_button.button('loading');
            form.find('.submit-container .alert').hide();

            this.registration.save(attributes, {
                success: function (model, response, options) {
                    if (response && response.message) {
                        var alert = form.find('.submit-container .alert-success');
                        alert.html('<strong>Success: </strong> ' + response.message).show();
                        alert.delay(3000).fadeOut();
                    }
                    submit_button.button('reset');
                    me.trigger('save_complete');
                },
                error: function (model, xhr, options) {
                    var alert = form.find('.submit-container .alert-danger');

                    var errorMsg = '';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else {
                        errorMsg = '<strong>Error: </strong> There was an error with your submission.  Please try again';
                    }

                    alert.html(errorMsg).show();
                    submit_button.button('reset');
                }
            });
        }
        return false;
    },

    submit_picture : function(event) {
        var me = this;

        var files = $(event.currentTarget);
        var submit_button = this.$el.find('.picture-upload-link');
        submit_button.button('loading');

        $.ajax(app.rest_root+'users/picture', {
            iframe: true,
            files: files,
            dataType: 'json'
        }).done(function(response) {
            submit_button.button('reset');
            if(response.status=="error") {
                var alert = me.$el.find('.submit-container .alert-danger');
                alert.show().text('The picture you are attempting to upload is too large.  Please choose a smaller image.');
            } else {
                me.$el.find('.profile-picture').attr('src', response.data.picture_url);
            }
        });
    }
});
'use strict';
app.BaseStepView = app.BaseFormView.extend({
    events: {
        'click .submit-container .previous': 'previous',
        'click .submit-container .next': 'next',
        'change .yes-no-more input': 'toggle_more_text'
    },

    render: function () {
        app.BaseFormView.prototype.render.call(this);

        var me = this;
        this.$el.find('.choices input').each(function (input) {
            var name = $(this).attr('name');
            var value = $(this).attr('value');
            if (me.model.get(name) === value) {
                $(this).parent().button('toggle');
            }
        });
        return this;
    },

    previous: function (event) {
        var attributes = this.$el.find('form').serializeObject();
        var me = this;

        this.model.save(attributes, {
            success: function (model, response, options) {
                if (response && response.message) {
                    if (response.data && response.data.uuid) {
                        me.model.set('uuid', response.data.uuid);
                    }

                    /* Remove the password since we don't want to send it back up again */
                    me.model.unset('password');
                    me.model.unset('confirm_password');
                }
                app.router.navigate(me.previous_url, true);
            }

        });

        return false;
    },

    next: function (event) {

        if (this.$el.find('form').valid()) {

            var attributes = this.$el.find('form').serializeObject();
            var me = this;

            var submit_button = this.$el.find('.submit-container button.submit');
            submit_button.button('loading');

            this.model.save(attributes, {
                success: function (model, response, options) {
                    if (response && response.message) {
                        if (response.data && response.data.uuid) {
                            me.model.set('uuid', response.data.uuid);
                        }

                        /* Remove the password since we don't want to send it back up again */
                        me.model.unset('password');
                        me.model.unset('confirm_password');
                    }
                    //submit_button.button('reset');
                    app.router.navigate(me.next_url, true);
                },
                error: function (model, xhr, options) {
                    var alert = me.$el.find('.submit-container .alert-danger');
                    alert.html('<strong>Error: </strong> There was an error with your submission.  Please try again').show();
                    submit_button.button('reset');
                }

            });
        }

        return false;
    },

    toggle_more_text: function (event) {
        var value = $(event.currentTarget).val();
        var more_field = this.$el.find('div.' + $(event.currentTarget).attr('name'));
        if (value === 'Yes') {
            more_field.show();
        } else {
            more_field.hide();
        }
    }

});
'use strict';
app.PlanView = app.BaseStepView.extend({
    id: 'plans',

    TYPE_ALACARTE: 'alacarte',
    TYPE_SUBSCRIPTION: 'subscription',

    chosen_plan_id: undefined,
    coupon_code: undefined,
    chosen_price: 0,
    chosen_plan_name: undefined,

    events: {
        'click .submit-container .previous': 'previous',
        'click .submit-container .submit': 'submit',
        'click a.choose': 'choose',
        'click a.cancel': 'cancel',
        'click button.cancel': 'cancel',
        'click .card-submit-container button.submit': 'submit_payment',
        'click .coupon-code-button': 'check_coupon_code',
        'click .coupon-code-reset-button': 'coupon_code_remove'
    },

    template_name: 'registration/plan',
    previous_url: '',

    initialize: function (options) {
        app.BaseStepView.prototype.initialize.call(this, options);

        /** Set the pricing value to be the actual json of the object **/
        this.options.pricing = this.options.pricing.toJSON();
    },

    render: function () {

        app.BaseStepView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });

        return this;
    },

    cancel: function (event) {
        $('.item-container').removeClass('inactive').removeClass('active');
        $(".card-fields").hide();

        $('.alacartes').show();
        $('.plans').show();
        return false;
    },

    choose: function (event) {

        this.chosen_plan_id = $(event.currentTarget).attr('data-id');
        this.chosen_price = $(event.currentTarget).closest('.plan-item').find('.price span').data('original');
        this.chosen_plan_name = $(event.currentTarget).closest('.plan-item').find('h4').text();
        $(event.currentTarget).closest('.item-container').addClass('active');
        $('.item-container:not(.active)').addClass('inactive');

        $(".card-fields").show();

        /* Decorate the plans that were chosen/unchosen */
        var type = $(event.currentTarget).attr('data-type');
        if (type === this.TYPE_ALACARTE) {
            this.choose_alacarte(event);
        } else {
            this.choose_subscription(event);
        }
        return false;
    },

    choose_subscription: function (event) {
        $('.alacartes').hide();
        $('.coupon-code-container').show();
    },

    choose_alacarte: function (event) {
        $('.plans').hide();
        $('.coupon-code-container').hide();
    },

    check_coupon_code: function (event) {
        var me = this;
        var coupon_input = $(event.currentTarget).closest('.input-group').find('input');
        var value = coupon_input.val();
        var price_field = $(".plan-" + this.chosen_plan_id + " .price span");
        $(".coupon-code-alerts .alert").hide();

        me.coupon_code = undefined;

        if (value) {
            $.ajax(app.rest_root + 'plans/coupon_code', {
                dataType: 'json',
                data: {
                    code: value,
                    plan_id: this.chosen_plan_id
                },
                success: function(response) {
                    price_field.text(response.data.price);
                    $(".coupon-code-alerts .alert-success").show();
                    $(event.currentTarget).hide();
                    me.$el.find('.coupon-code-reset-button').show();
                    me.coupon_code = value;

                },
                error : function() {
                    $(".coupon-code-alerts .alert-danger").show();
                    coupon_input.val('');
                    me.coupon_code = '';

                }
            });
        } else {
            price_field.val(price_field.attr('data-original'));
        }
    },

    coupon_code_remove : function(event) {
        this.$el.find('.coupon-code-button').show();
        $(".coupon-code-alerts .alert").hide();
        $(event.currentTarget).hide();
        this.coupon_code = '';
        var price_field = $(".plan-" + this.chosen_plan_id + " .price span");
        price_field.text(price_field.data('original'));
        $(event.currentTarget).closest('.input-group').find('input').val('');
        return false;
    },

    record_conversion : function(event, response) {
        if(window._fbq && window._fbq.push) {
            window._fbq.push(['track', '6030582505998', {'value':this.chosen_price,'currency':'USD'}]);
        }

        console.log(ga);
        ga('ecommerce:addTransaction', {
            'id': response.user.uuid,
            'affiliation': 'Blush',
            'revenue': this.chosen_price,
            'shipping': 0,
            'tax': 0
        });

        ga('ecommerce:addItem', {
            'id': response.user.uuid,
            'name': this.chosen_plan_name,
            'sku': '',
            'category': '',
            'price': this.chosen_price,
            'quantity': 1
        });

        ga('ecommerce:send');
    },

    submit_payment: function (event) {
        var me = this;
        var form = $(event.currentTarget).closest('form');

        var submit_button = $(event.currentTarget);

        if (form.valid()) {
            submit_button.button('loading');
            Stripe.createToken({
                number: $(form).find('.card-number').val(),
                cvc: $(form).find('.card-cvc').val(),
                exp_month: $(form).find('.card-expiry-month').val(),
                exp_year: $(form).find('.card-expiry-year').val(),
                name: $(form).find(".cardholder-name").val()
            }, function (status, response) {

                if (response.error) {
                    var alert = form.find('.alert-danger');
                    alert.show().text(response.error.message);
                    submit_button.button('reset');
                } else {

                    var attributes = {
                        chosen_plan_id: me.chosen_plan_id,
                        code: me.coupon_code,
                        token: response['id'],
                        completed: 1
                    }

                    me.model.save(attributes, {
                        success: function (model, response) {
                            submit_button.button('reset');

                            var data = response.data;

                            if (data && data.inactive > 0) {
                                window.location = app.app_root + 'login/'
                            }
                            me.record_conversion(event, response.data);
                            window.location = app.app_root + 'dashboard/';
                        },
                        error: function (model, xhr) {
                            var alert = me.$el.find('.submit-container .alert-danger');

                            var errorMsg = '';
                            if(xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else {
                                errorMsg = '<strong>Error: </strong> There was an error with your submission.  Please try again';
                            }

                            alert.html(errorMsg).show();
                            submit_button.button('reset');
                        }
                    });
                }
            });
        }
        return false;
    }
});
'use strict';
app.Step1View = app.BaseStepView.extend({
    VAL_MY_COUNSELOR : 'My Coach',
    VAL_OTHER : 'Other',

    events: {
        'click .submit-container .previous': 'previous',
        'click .submit-container .next': 'next',
        'change .yes-no-more input': 'toggle_more_text',
        'change #referral' : 'referral_source',
        'change #birthday' : 'validate_birthday',
        'click .non-us' : 'toggle_state'
    },

    template_name: 'registration/step1',
    next_url: 'plan',

    render: function () {
        if(!this.model.get('timezone')) {
            this.model.set('timezone', app.get_timezone());
        }
        app.BaseStepView.prototype.render.call(this);
        this.$el.find('.datepicker').datepicker({
            autoclose: true
        });

        return this;
    },

    referral_source : function(event) {
        this.$el.find('div.counselor').hide().find('input').removeAttr('data-rule-required');
        this.$el.find('div.other').hide().find('input').removeAttr('data-rule-required');

        if($(event.currentTarget).val()==this.VAL_MY_COUNSELOR) {
            this.$el.find('div.counselor').show().find('input').attr('data-rule-required', 'true');
        } else if($(event.currentTarget).val()==this.VAL_OTHER) {
            this.$el.find('div.other').show().find('input').attr('data-rule-required', 'true');
        }
    },

    toggle_state: function(event) {
        var checked = $(event.currentTarget).prop('checked');
        console.log(checked);
        if(checked) {
            this.$el.find('#state').hide();
        } else {
            this.$el.find('#state').show();
        }
    },

    /* If the visitor is < 13, they cannot register.  If they are < 18, they must provide a parent's email address */
    validate_birthday : function(event) {
        var container = $('.alert-container');
        app.clear_message(container);
        var age = app.calculate_age($(event.currentTarget).val());
        if(age<13) {
            this.$el.find('.btn-primary').attr('disabled','disabled');
            app.error_message('You must be over the age of 13 in order to use Blush.', container, 30000);
            return;
        } else if(age<18) {
            this.$el.find('.btn-primary').removeAttr('disabled');
            this.$el.find('.parent-email-container').show();
            $("#parent_consent").attr('data-rule-required', true);
            return;
        } else {
            this.$el.find('.btn-primary').removeAttr('disabled');
            this.$el.find('.parent-email-container').hide();
        }


        this.$el.find('.submit').removeAttr('disabled');
        $("#parent_email").removeAttr('data-rule-required');
        return false;
    }
});
'use strict';
app.Step2View = app.BaseStepView.extend({

    template_name: 'registration/step2',
    previous_url: 'step1',
    next_url: 'step3'
});
'use strict';
app.Step3View = app.BaseStepView.extend({

    template_name: 'registration/step3',
    previous_url: 'step2',
    next_url: 'step4'
});
'use strict';
app.Step4View = app.BaseStepView.extend({

    template_name: 'registration/step4',
    previous_url: 'step3',
    next_url: 'step5'
});
'use strict';
app.Step5View = app.BaseStepView.extend({

    template_name: 'registration/step5',
    previous_url: 'step4',
    next_url: 'plan',

    /* Override parent next() function and do the save
    next: function (event) {

        if (this.$el.find('form').valid()) {
            this.record_conversion();

            var attributes = this.$el.find('form').serializeObject();
            attributes.completed = 1;
            var me = this;

            var submit_button = this.$el.find('.submit-container button.submit');
            submit_button.button('loading');

            this.model.save(attributes, {
                success: function (model, response, options) {
                    var data = response.data;
                    if(data && data.inactive>0) {
                        window.location = app.app_root + 'login/'
                    }
                    window.location = app.app_root + 'dashboard/';
                }
            });
        }

        return false;
    },*/

    record_conversion : function(event) {
        /* Sign up conversion */
        var google_conversion_id = 972795131;
        var google_conversion_language = "en";
        var google_conversion_format = "2";
        var google_conversion_color = "ffffff";
        var google_conversion_label = "wJAjCKWExQcQ-9nuzwM";
        var google_conversion_value = 0;
        var google_remarketing_only = false;
        $.getScript( "http://www.googleadservices.com/pagead/conversion.js" );

        var fb_param = {};
        fb_param.pixel_id = '6014439113798';
        fb_param.value = '0.00';
        fb_param.currency = 'USD';
        (function(){
            var fpw = document.createElement('script');
            fpw.async = true;
            fpw.src = '//connect.facebook.net/en_US/fp.js';
            var ref = document.getElementsByTagName('script')[0];
            ref.parentNode.insertBefore(fpw, ref);
        })();
    }
});