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