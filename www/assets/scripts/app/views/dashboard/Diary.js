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