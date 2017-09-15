var app = {
    admin: {},
    registration: {},
    myAccount: {},
    app_root: '/app/',
    rest_root: '/app/rest/',
    admin_root: '/app/admin/',
    alert_container: '#app-alert-container',

    loggly: undefined,

    user_type_customer: 1,
    user_type_counselor: 2,
    user_type_admin: 99,

    log: function (message, data) {
        data = data || {};
        data.text = message;
        data.client = 'blush';

        if (message) {
            app.loggly.push(data);
        }
    },

    /* Since the footer is shared with the wordpress instance, we're going to bring it into both sites via ajax */
    load_footer: function () {
        /* Don't load the footer on the admin side */
        if (jQuery('body#admin').length > 0) {
            return;
        }

        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'blush_footer'
            }
        }).done(function (data) {
                jQuery("#footer").html(data);
            });

        /* Setup the contact us link click */
        jQuery("#footer").on('click', '#menu-item-30', function () {
            app.load_contact_modal();
            return false;
        });

        /* Setup the contact us link click */
        jQuery("#footer").on('click', '.spotify a', function () {
            app.load_spotify_modal();
            return false;
        });

        /* Setup the send button */
        jQuery("body").on('click', '.gravity-modal .btn-primary', function (event) {
            jQuery(event.currentTarget).closest('.modal-content').find('.gform_button').click();
        });
    },

    /**
     * Loads the pricing from the back-end REST service and updates the home page.
     * Called by the front-page.php in the theme.
     */
    load_pricing: function () {
        jQuery.ajax({
            url: app.rest_root + 'plans/pricing',
            type: 'GET'
        }).done(function (data) {
                jQuery('#pricing-journal h5 span').text(data.blush_journal);
                jQuery('#pricing-video h5 span').text(data.blush_video);

                _.each(data.plans, function (plan) {
                    jQuery("#" + plan.stripe_plan_id + " h5 span").text(plan.price);
                    jQuery("#" + plan.stripe_plan_id + " .credits strong").text(plan.credits);
                });
            });
    },
    /**
     * Opens the "Contact Us" modal on the home page and from the footer of the site -- pulls the form from wordpress
     */
    load_contact_modal: function () {
        var me = this;

        if (jQuery("#contact-modal").length > 0) {
            jQuery("#contact-modal").modal('show');
        } else {
            var data = {};
            if (app.user) {
                data.fullname = app.user.firstname + ' ' + app.user.lastname;
                data.phone = app.user.phone;
                data.email = app.user.email;
            }

            jQuery.get('/contact-us-popup-window/', data, function (data) {
                jQuery('body').append(data);
                jQuery("#contact-modal").modal('show');
                me.gravity_placeholders();
            });
        }
    },

    /**
     * Opens the "Spotify Playlist" modal on the home page and from the footer of the site
     */
    load_spotify_modal: function () {

        if (jQuery("#spotify-modal").length > 0) {
            jQuery("#spotify-modal").modal('show');
        } else {
            jQuery.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'spotify_modal'
                }
            }).done(function (data) {
                    jQuery('body').append(data);
                    jQuery("#spotify-modal").modal('show');
                });
        }
    },

    /**
     * Opens the "Ask A Question" modal on the home page -- pulls the form from wordpress
     */
    load_ask_question_modal: function () {
        var me = this;

        if (jQuery("#ask-question-modal").length > 0) {
            jQuery("#ask-question-modal").modal('show');
        } else {

            jQuery.get('/ask-a-question-popup-window/', {}, function (data) {
                jQuery('body').append(data);
                jQuery("#ask-question-modal").modal('show');
                me.gravity_placeholders();
            });
        }
    },

    /**
     * Opens the "Ask A Question" modal on the home page -- pulls the form from wordpress
     */
    load_dear_blush_modal: function () {
        var me = this;

        if (jQuery("#dear-blush-modal").length > 0) {
            jQuery("#dear-blush-modal").modal('show');
        } else {

            jQuery.get('/dear-blush-popup-window/', {}, function (data) {
                jQuery('body').append(data);
                jQuery("#dear-blush-modal").modal('show');
                me.gravity_placeholders();
            });
        }
    },

    /**
     * Opens the "Ask A Question" modal on the home page -- pulls the form from wordpress
     */
    load_tell_story_modal: function () {
        var me = this;

        if (jQuery("#tell-story-modal").length > 0) {
            jQuery("#tell-story-modal").modal('show');
        } else {

            jQuery.get('/tell-us-your-story-popup-window/', {}, function (data) {
                jQuery('body').append(data);
                jQuery("#tell-story-modal").modal('show');
                me.gravity_placeholders();
            });
        }
    },

    /**
     * Opens the "Contact Us" modal on the home page and from the footer of the site -- pulls the form from wordpress
     */
    load_apply_job_modal: function () {
        var me = this;

        if (jQuery("#apply-job-modal").length > 0) {
            jQuery("#apply-job-modal").modal('show');
        } else {

            jQuery.get('/apply-job-popup-window/', {}, function (data) {
                jQuery('body').append(data);
                jQuery("#apply-job-modal").modal('show');
                me.gravity_placeholders();
            });
        }
    },

    /**
     * Automatically hides all labels related to forms and puts their text in the associated input as a placeholder
     */
    gravity_placeholders: function () {
        jQuery('.gform_fields li').each(function () {
            if (jQuery(this).hasClass('no-placeholder') || jQuery(this).find('.ginput_complex').length > 0) {
                return;
            } else {
                var label = jQuery(this).find('label').text();
                jQuery(this).find('input').attr('placeholder', label);
                jQuery(this).find('textarea').attr('placeholder', label);
                jQuery(this).find('select').attr('placeholder', label);
                jQuery(this).find('label').addClass('sr-only');
            }
        });

        jQuery('.gform_fields li .ginput_complex span').each(function () {
            var label = jQuery(this).find('label').text();
            jQuery(this).find('input').attr('placeholder', label);
            jQuery(this).find('label').addClass('sr-only');
        });
    },

    fetch_error: function (model_or_collection, response, options) {
        if (response && response.responseText) {
            if ($('.alert-container').length > 0) {
                app.error_message('<strong>Error:</strong> ' + response.responseText, $('.alert-container'), 15000);
            } else if (console) {
                console.error(response);
                app.log(response, {
                    error: response,
                    method: 'app.fetch_error',
                    xhr: xhr
                });
            }
        }
    },

    success_message: function (message, container, delay) {
        container = container || app.alert_container;
        if (!container.jquery || !container.empty) {
            container = $(container);
        }
        if (!delay) {
            delay = 5000;
        }

        if (container && message && container.empty) {
            container.empty().show();
            container.append('<p class="alert alert-success">' + message + '</p>');
            container.delay(delay).fadeOut(400);
        }
    },

    error_message: function (message, container, delay) {
        if (!container.jquery || !container.empty) {
            container = $(container);
        }
        if (!delay) {
            delay = 5000;
        }
        if (container && message && container.empty) {
            container.empty().show();
            container.append('<p class="alert alert-danger">' + message + '</p>');
            container.delay(delay).fadeOut(400);
        }
    },

    clear_message: function (container) {
        container.empty().hide();
    },

    fetch_upcoming_event: function (view) {
        $.get(app.rest_root + 'events/upcoming', function (response) {
            if (response && response.uuid) {
                view.$el.find('.upcoming-event .start-session').attr('data-id', response.uuid);
                view.$el.find('.upcoming-event').show();
                if (response.minutes_remaining >= 0) {
                    view.$el.find('.upcoming-event .text').html(_.template(app.msg.upcoming_session, response));
                } else {
                    response.minutes_remaining = response.minutes_remaining * -1;
                    view.$el.find('.upcoming-event .text').html(_.template(app.msg.ongoing_session, response));
                }
            }
        })
    },

    calculate_age: function (dateString) {
        var birthday = +new Date(dateString);
        return ~~((Date.now() - birthday) / (31557600000));
    },

    /**
     * Returns the timestamp of 24 hours from now
     */
    calculate_24hours: function () {
        var date = new Date(new Date().getTime() + 60 * 60 * 24 * 1000);
        return date;
    },

    /**
     * When passed in a date and time object, returns the actual Date representation of it.
     * @param date Format "mm/dd/YYYY"
     * @param time Format "HH:mm"
     */
    date_from_input: function (date_string, time_string) {
        if (date_string && time_string) {
            var date_pieces = date_string.split("/");
            var time_pieces = time_string.split(":");

            var date = new Date();
            date.setMonth(parseInt(date_pieces[0]) - 1);
            date.setDate(date_pieces[1]);
            date.setYear(date_pieces[2]);

            date.setHours(time_pieces[0]);
            date.setMinutes(time_pieces[1]);
            date.setSeconds(time_pieces[2]);
            return date;
        }
    },

    get_timezone_offset: function () {
        var dtDate = new Date('1/1/' + (new Date()).getUTCFullYear());
        var intOffset = 10000; //set initial offset high so it is adjusted on the first attempt
        var intMonth;
        var intHoursUtc;
        var intHours;
        var intDaysMultiplyBy;

        //go through each month to find the lowest offset to account for DST
        for (intMonth = 0; intMonth < 12; intMonth++) {
            //go to the next month
            dtDate.setUTCMonth(dtDate.getUTCMonth() + 1);

            //To ignore daylight saving time look for the lowest offset.
            //Since, during DST, the clock moves forward, it'll be a bigger number.
            if (intOffset > (dtDate.getTimezoneOffset() * (-1))) {
                intOffset = (dtDate.getTimezoneOffset() * (-1));
            }
        }

        return intOffset/60;
    },

    get_timezone : function() {
        var offset = this.get_timezone_offset();
        var timezones = {
            '-10' : 'America/Adak',
            '-9' : 'America/Anchorage',
            '-8' : 'America/Los_Angeles',
            '-7' : 'America/Denver',
            '-6' : 'America/Chicago',
            '-5' : 'America/New_York'
        };

        return timezones[offset.toString()];
    },

    get_router: function() {
        return app.router;
    },

    msg: {
        upcoming_session: 'Get ready, you have a video session starting in <%=minutes_remaining%> minute(s)',
        ongoing_session: '<strong>Are you ready?</strong> Your video session started <%=minutes_remaining%> minute(s) ago'
    },

    msg_success: {
        credits_purchase: 'Your credits have been purchased successfully.'
    },

    msg_error: {
        credits_invalid_diary: 'You do not have enough Blush Credits in order to create a new diary.  Please purchase more credits by clicking the "Add Credits" button on the right',
        credits_invalid_video: 'You do not have enough Blush Credits in order to schedule a new video session.  Please purchase more credits by clicking the "Add Credits" button on the right',
        no_counselor: 'You do not currently have a Blush Coach assigned to you.  Be patient and one will be assigned to you soon.'
    }
};

jQuery(document).ready(function () {

    jQuery.ajaxSetup({
        cache: false
    });
    app.load_footer();
    app.gravity_placeholders();

    app.loggly = _LTracker || [];
    app.loggly.push({'logglyKey': 'c27a2843-94a4-43d4-a4eb-55908ee737f6' });
});