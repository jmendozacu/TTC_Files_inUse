<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Resource_EmailLanding_Design_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_datamart/emailLanding_design');
    }
}
