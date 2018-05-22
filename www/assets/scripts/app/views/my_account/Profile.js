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
            console.log("attributes,", attributes);
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