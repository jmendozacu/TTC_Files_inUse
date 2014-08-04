
Validation.add('freelecture-password-required', 'Please enter a password.', function(v) {
    return !Validation.get('IsEmpty').test(v);
});

Validation.add('freelecture-email-valid', 'Sorry, this email address isn&#39t formatted correctly. Please re-enter it.', function(v) {
    return Validation.get('IsEmpty').test(v) || /^([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9,!\#\$%&'\*\+\/=\?\^_`\{\|\}~-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*@([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z0-9-]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*\.(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]){2,})$/i.test(v)
});

Validation.add('freelecture-confirm-password-newcustomer', 'This does not match the password you entered above. Please retype.', function(v) {
    var validationLectureCheck = true;

    if(jQuery('input#newcustomer-freelecture-password-confirm').val() !=
        jQuery('input#newcustomer-freelecture-password').val()) {
        validationLectureCheck = false;
    }

    return validationLectureCheck;
});