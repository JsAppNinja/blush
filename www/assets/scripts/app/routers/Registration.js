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