Validation.add('validate-telephone-uk', 'Please use only numbers (0-9), hyphens or space, first digit should be 0.', function(v) {
    if (Validation.get('IsEmpty').test(v)) {
        return true;
    }
    // Convert into a string and check that we were provided with something
    var telnum = v + " ";
    if (telnum.length == 1)  {
        return false
    }
    telnum = telnum.trim();

    telnum.length = telnum.length - 1;

    // Don't allow country codes to be included (assumes a leading "+")
    var exp = /^(\+)[\s]*(.*)$/;
    if (exp.test(telnum) == true) {
        return false;
    }

    // Remove spaces from the telephone number to help validation
    while (telnum.indexOf(" ")!= -1)  {
        telnum = telnum.slice (0,telnum.indexOf(" ")) + telnum.slice (telnum.indexOf(" ")+1)
    }

    // Remove hyphens from the telephone number to help validation
    while (telnum.indexOf("-")!= -1)  {
        telnum = telnum.slice (0,telnum.indexOf("-")) + telnum.slice (telnum.indexOf("-")+1)
    }

    // Now check that all the characters are digits
    exp = /^[0-9]{10,11}$/;
    if (exp.test(telnum) != true) {
        return false;
    }

    // Now check that the first digit is 0
    exp = /^0[0-9]{9,10}$/;
    if (exp.test(telnum) != true) {
        return false;
    }

    // Finally check that the telephone number is appropriate.
    exp = (/^(01|02|03|05|07|08|09)[0-9]+$/);
    if (exp.test(telnum) != true) {
        return false;
    }

    return true;
});