<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Helper_Data extends Mage_Core_Helper_Data
{
    private $_primarySubjectAttributeId;

    /**
     * Returns 'primary_subject' attribute id
     *
     * @return int
     */
    public function getPrimarySubjectAttributeId()
    {
        if (is_null($this->_primarySubjectAttributeId)) {
            $this->_primarySubjectAttributeId = Mage::getResourceSingleton('eav/entity_attribute')
                ->getIdByCode(Mage_Catalog_Model_Product::ENTITY, 'primary_subject');
        }
        return $this->_primarySubjectAttributeId;
    }
}
