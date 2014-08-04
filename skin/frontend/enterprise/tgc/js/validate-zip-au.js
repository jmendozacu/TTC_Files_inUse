Validation.addAllThese([
    ['validate-zip-au', 'Please enter a valid Australia Postcode.', function(v) {
        return Validation.get('IsEmpty').test(v) || /(^\d{4}$)/.test(v);
    }]
]);
