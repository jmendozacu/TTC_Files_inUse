<?php
/**
 * DataMart integration
 *
 * @method int getId() getId() Returns email landing entity id
 * @method string getCategory() getCategory() Returns category
 * @method int getHeaderId() getHeaderId() Returns header block id
 * @method int getFooterId() getFooterId() Returns footer block id
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_EmailLanding_Design extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'email_landing_design';
    protected $_eventObject = 'emailLanding_design';

    protected function _construct()
    {
        $this->_init('tgc_datamart/emailLanding_design');
    }
}
