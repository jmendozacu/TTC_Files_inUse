/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

Validation.add('validate-boutique-identifier', 'Please enter a valid URL Key. For example "example-page".', function (v) {
    if (v.indexOf('/') >= 0) {
        return false;
    }
    return Validation.get('IsEmpty').test(v) || /^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/.test(v)
});
