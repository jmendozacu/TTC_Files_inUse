/**
 * Customer validation
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

Validation.add('validate-new-password', 'Please enter a password that is between 5 and 20 characters long.', function (v) {
    var pass = v.strip();
    if (0 == pass.length) {
        return true;
    }
    return !(pass.length < 5 || pass.length > 20);
});

Validation.add('validate-password', 'Please enter a password that is between 5 and 20 characters long.', function (v) {
    var pass = v.strip();
    if (0 == pass.length) {
        return true;
    }
    return !(pass.length < 5 || pass.length > 20);
});
