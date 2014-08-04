<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_EmailUnsubscribe extends Mage_Core_Model_Abstract
{
    const CACHE_TAG              = 'email_unsubscribe';
    const WEB_KEY_PARAM          = 'ai';
    const EMAIL_PARAM            = 'em';
    const EMAIL_CAMPAIGN_PARAM   = 'cm_mmc';
    const ALT_WEB_KEY_PARAM      = 'cm_mmca1';

    protected $_eventPrefix = 'email_unsubscribe';
    protected $_eventObject = 'emailUnsubscribe';

    protected function _construct()
    {
        $this->_init('tgc_dax/emailUnsubscribe');
    }
}
