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