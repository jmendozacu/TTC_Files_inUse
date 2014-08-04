Validation.add('validate-zip-uk', '', function(v) {
    if (Validation.get('IsEmpty').test(v)) {
        return true;
    }
    var alpha1 = "[abcdefghijklmnoprstuwyz]";                       // Character 1
    var alpha2 = "[abcdefghklmnopqrstuvwxy]";                       // Character 2
    var alpha3 = "[abcdefghjkpmnrstuvwxy]";                         // Character 3
    var alpha4 = "[abehmnprvwxy]";                                  // Character 4
    var alpha5 = "[abdefghjlnpqrstuwxyz]";                          // Character 5
    var BFPOa5 = "[abdefghjlnpqrst]";                               // BFPO alpha5
    var BFPOa6 = "[abdefghjlnpqrstuwzyz]";                          // BFPO alpha6

    // Array holds the regular expressions for the valid postcodes
    var pcexp = new Array ();

    // BFPO postcodes
    pcexp.push (new RegExp ("^(bf1)(\\s*)([0-6]{1}" + BFPOa5 + "{1}" + BFPOa6 + "{1})$","i"));

    // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA
    pcexp.push (new RegExp ("^(" + alpha1 + "{1}" + alpha2 + "?[0-9]{1,2})(\\s*)([0-9]{1}" + alpha5 + "{2})$","i"));

    // Expression for postcodes: ANA NAA
    pcexp.push (new RegExp ("^(" + alpha1 + "{1}[0-9]{1}" + alpha3 + "{1})(\\s*)([0-9]{1}" + alpha5 + "{2})$","i"));

    // Expression for postcodes: AANA  NAA
    pcexp.push (new RegExp ("^(" + alpha1 + "{1}" + alpha2 + "{1}" + "?[0-9]{1}" + alpha4 +"{1})(\\s*)([0-9]{1}" + alpha5 + "{2})$","i"));

    // Exception for the special postcode GIR 0AA
    pcexp.push (/^(GIR)(\s*)(0AA)$/i);

    // Standard BFPO numbers
    pcexp.push (/^(bfpo)(\s*)([0-9]{1,4})$/i);

    // c/o BFPO numbers
    pcexp.push (/^(bfpo)(\s*)(c\/o\s*[0-9]{1,3})$/i);

    // Overseas Territories
    pcexp.push (/^([A-Z]{4})(\s*)(1ZZ)$/i);

    // Anguilla
    pcexp.push (/^(ai-2640)$/i);

    var valid = false;

    // Check the string against the types of post codes
    for ( var i=0; i<pcexp.length; i++) {
        if (pcexp[i].test(v)) {
            valid = true;
            break;
        }
    }
    return valid;
});
