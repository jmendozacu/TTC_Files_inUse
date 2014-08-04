<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Audio_List extends Tgc_DigitalLibrary_Block_List_Abstract
{
    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $access = Mage::getResourceModel('tgc_dl/accessRights');
            $customer = $this->_getCustomer();

            $this->_productCollection = $access->getCoursesCollectionForCustomer($customer, Tgc_DigitalLibrary_Model_Source_Format::AUDIO);
        }

        return $this->_productCollection;
    }
}
