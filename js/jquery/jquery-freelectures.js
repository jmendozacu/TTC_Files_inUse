jQuery(document).ready(function() {
    jQuery("#free-lectures-form-validate-forgotpassword").hide();

    jQuery("a.existingcustomer").on('click', function(e) {
        e.preventDefault();
        jQuery("#free-lectures-form-validate-forgotpassword").hide();
        jQuery("#free-lectures-form-validate-notsubscribed").hide();
        jQuery("#free-lectures-form-validate-subscribed").hide();
        jQuery("#free-lectures-form-validate-newcustomer").hide();
        jQuery("#free-lectures-form-validate-existingcustomer").show();
        jQuery("#free-lectures-form-validate-newcustomer .email-block .validation-advice").remove();
        jQuery("#free-lectures-form-validate-newcustomer .email-block input[type=text]").removeClass('validation-failed');
        jQuery("#free-lectures-form-validate-existingcustomer .freelecture-email-valid").attr('name', 'freelectures[email_address]');
        jQuery("#free-lectures-form-validate-newcustomer .freelecture-email-valid").attr('name', '');

    });

    jQuery("a.newcustomer").on('click', function(e) {
        e.preventDefault();
        jQuery("#free-lectures-form-validate-existingcustomer").hide();
        jQuery("#free-lectures-form-validate-forgotpassword").hide();
        jQuery("#free-lectures-form-validate-notsubscribed").hide();
        jQuery("#free-lectures-form-validate-subscribed").hide();
        jQuery("#free-lectures-form-validate-newcustomer").show();
        jQuery("#free-lectures-form-validate-existingcustomer .email-block .validation-advice").remove();
        jQuery("#free-lectures-form-validate-existingcustomer .email-block input[type=text]").removeClass('validation-failed');
        jQuery("#free-lectures-form-validate-newcustomer .freelecture-email-valid").attr('name', 'freelectures[email_address]');
        jQuery("#free-lectures-form-validate-existingcustomer .freelecture-email-valid").attr('name', '');
    });

    jQuery(".visible-xs").on('click', function(e) {
        if (jQuery("#player").hasClass("hidden-player")) {
            jQuery("#player").removeClass("hidden-player")
            jQuery("#player").show();
        }
        if (!jQuery(this).hasClass('show-player')) {
            jQuery(this).addClass('show-player');
            jQuery(this).unbind('click');
        }
    });

    jQuery("a.freelecture-forgotpassword").on('click', function(e) {
        e.preventDefault();
        jQuery("#free-lectures-form-validate-existingcustomer").hide();
        jQuery("#free-lectures-form-validate-notsubscribed").hide();
        jQuery("#free-lectures-form-validate-subscribed").hide();
        jQuery("#free-lectures-form-validate-newcustomer").hide();
        jQuery("#free-lectures-form-validate-forgotpassword").show();
    });

    jQuery("a#freelecture-backtoreg").on('click', function(e) {
        e.preventDefault();
        jQuery("#free-lectures-form-validate-existingcustomer").hide();
        jQuery("#free-lectures-form-validate-forgotpassword").hide();
        jQuery("#free-lectures-form-validate-notsubscribed").hide();
        jQuery("#free-lectures-form-validate-subscribed").hide();
        jQuery("#free-lectures-form-validate-newcustomer").hide();
        jQuery("#free-lectures-form-validate-existingcustomer").show();
    });
});