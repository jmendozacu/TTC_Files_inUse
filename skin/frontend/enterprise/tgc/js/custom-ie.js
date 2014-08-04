//Styles for checkboxes
var checkbox = jQuery('input[type="checkbox"]');

checkbox.each(function () {
    if (jQuery(this).is(':checked')) {
        jQuery(this).addClass('checked');
    } else {
        jQuery(this).addClass('unchecked');
    }

    jQuery(this).on('change', function () {
        if (jQuery(this).hasClass('checked')) {
            jQuery(this).removeClass('checked').addClass('unchecked');
        } else {
            jQuery(this).removeClass('unchecked').addClass('checked');
        }
    });
});
