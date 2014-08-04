<?php
/**
 * DataMart integration
 *
 * @method int getId() getId() Returns email landing entity id
 * @method string getCategory() getCategory() Returns category
 * @method int getCourseId() getCourseId() Returns course id
 * @method float getSortOrder() getsortOrder() Returns sort order
 * @method bool getMarkdownFlag() getMarkdownFlag() Returns markdown flag value
 * @method string getSpecialMessage() getSpecialMessage() Returns special message
 * @method string getDateExpires() getDateExpires() Returns expiry date
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_EmailLanding extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'email_landing';

    protected $_eventPrefix = 'email_landing';
    protected $_eventObject = 'emailLanding';

    protected function _construct()
    {
        $this->_init('tgc_datamart/emailLanding');
    }
}
