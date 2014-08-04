jQuery(document).ready(function() {
    //This statement adds the asterisk next to Welcome Message and more details fields, when the page loads.
    if(jQuery("select#ad_type").val() == 1) {
        jQuery("label[for='pid']").append("<span class='required'>*</span>");
        jQuery("label[for='more_details']").append("<span class='required'>*</span>");
    }

    if(jQuery("select#ad_type").val() != 2) {
        jQuery("#header_cms_desktop").hide();
        jQuery("#header_cms_mobile").hide();
        jQuery("label[for='header_cms_desktop']").hide();
        jQuery("label[for='header_cms_mobile']").hide();
    }

    jQuery("select#ad_type").change(function(){
        //This adds an asterisk next to Welcome Message or More details, if the users selects ad type of 'Space Ad' from the drop down.
        if(jQuery("select#ad_type").val() == 1) {
            jQuery("label[for='pid']").append("<span class='required'>*</span>");
            jQuery("label[for='more_details']").append("<span class='required'>*</span>");
        }

        if(jQuery("select#ad_type").val() == 2) {
            jQuery("#header_cms_desktop").show();
            jQuery("#header_cms_mobile").show();
            jQuery("label[for='header_cms_desktop']").show();
            jQuery("label[for='header_cms_mobile']").show();
        } else {
            jQuery("#header_cms_desktop").hide();
            jQuery("#header_cms_mobile").hide();
            jQuery("label[for='header_cms_desktop']").hide();
            jQuery("label[for='header_cms_mobile']").hide();
        }

        //This removes an asterisk next to Welcome Message or More details, if the users selects ad type of 'Space Ad' from the drop down.
        if(jQuery("select#ad_type").val() == 0) {
            jQuery("label[for='pid']").find('span').remove();
            jQuery("label[for='more_details']").find('span').remove();
        }
    });
});

Validation.add('spacecode', 'This is a required field', function(v) {
    var validationLectureCheck = true;

    if(jQuery('select#ad_type').val() == 1) { //One correponds to 'Space Code'
        validationLectureCheck = !Validation.get('IsEmpty').test(v)
    }

    return validationLectureCheck;
});

Validation.add('required-adcode-field', 'One of the following fields must be filled in: Course ID, Category ID, Professor, CMS Page', function(v) {
    return  jQuery('#course_id').val() 
        || jQuery('#professor_id').val()
        || jQuery('#category_id').val()
        || jQuery('#cms_page_id').val();
});