
Validation.add('checkuniquesort', 'Please enter a lecture number that does not already exist.', function(v) {
    //return false;
    var hasMatch = false;
    jQuery('table#lectures_grid_table .lecture-number').each(function() {
        if ( jQuery(this).text().trim()  == jQuery('input#lecture_number').val() && !jQuery(this).hasClass('currentRequestedItem')) {
            hasMatch = true;
        }
    });

    if (hasMatch) {
        return window.confirm('The lecture number you entered already exists.\n' +
            'If you click yes, all lecture numbers that are greater than or equal to what you typed in will be increased by one.\n' +
            'If you click no, you will return to the form and can change the lecture number.');
    } else return true;
});


Validation.add('lecture-validation-required', 'This is a required field.', function(v) {
    var validationLectureCheck = true;

    if(jQuery('input#audio_brightcove_id').val() || jQuery('input#video_brightcove_id').val() || jQuery('input#akamai_download_id').val()) {
        validationLectureCheck = !Validation.get('IsEmpty').test(v)
    }

    return validationLectureCheck;
});
