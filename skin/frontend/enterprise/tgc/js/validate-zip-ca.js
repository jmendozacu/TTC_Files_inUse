Validation.addAllThese([
    ['validate-zip-ca', 'Please enter a valid Canada postcode.', function(v) {
        return Validation.get('IsEmpty').test(v) || /(^\s*[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d\s*$)/.test(v);
    }]
]);
