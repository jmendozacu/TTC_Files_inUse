<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Resource_EmailUnsubscribe_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_dax/emailUnsubscribe');
    }

    public function addExportFilter()
    {
        $this->addFieldToFilter('is_archived', array('eq' => 0));

        return $this;
    }
}
