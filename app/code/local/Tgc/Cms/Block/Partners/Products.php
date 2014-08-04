<?php
/**
 * User: mhidalgo
 * Date: 22/04/14
 * Time: 10:37
 */
class Tgc_Cms_Block_Partners_Products extends Mage_Catalog_Block_Product_Abstract
{
    protected $_partner = null;
    protected $_productCollection = null;
    protected $_professor = null;
    protected $_partnerImage = null;

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('partners/partnerProducts.phtml');
    }

    /**
     * @param null|string|integer $partner
     * @return $this|bool
     */
    public function setPartner($partner = null) {
        if (is_null($partner)) {
            $partner = $this->getShowPartner();
        }
        if (is_string($partner) && !intval($partner)) {
            $partner = Mage::getModel('tgc_cms/partners')->load($partner, 'alt_text');
        } elseif (is_numeric($partner)) {
            $partner = Mage::getModel('tgc_cms/partners')->load($partner);
        }

        if (is_null($partner) || !$partner->getId()) {
            $partner = Mage::getModel('tgc_cms/partners')->getCollection()->getFirstItem();
        }

        $this->_partner = $partner;

        return $this;
    }

    /**
     * @return Tgc_Cms_Model_Partners
     */
    public function getPartner() {
        if (is_null($this->_partner)) {
            $this->setPartner();
        }
        return $this->_partner;
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductCollection() {
        if (is_null($this->_productCollection)) {
            $partner = $this->getPartner();
            /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
            $collection = Mage::getResourceModel('catalog/product_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToFilter('partner', $partner->getId())
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addPriceData();

            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    /**
     * Retrieve attribute instance by name, id or config node
     *
     * If attribute is not found false is returned
     *
     * @param string|integer|Mage_Core_Model_Config_Element $attribute
     * @return Mage_Eav_Model_Entity_Attribute_Abstract || false
     */
    public function getProductAttribute($attribute)
    {
        return $this->getProduct()->getResource()->getAttribute($attribute);
    }
}