<?php

class Tgc_Datamart_Block_EmailLanding_Buffet_Product_List extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_productCollection;

    /**
     * Return store id.
     *
     * @return integer
     */
    public function getStoreId()
    {
        if ($this->hasData('store_id')) {
            return $this->_getData('store_id');
        }
        return Mage::app()->getStore()->getId();
    }

    /**
     * Retrieve product collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            if (!$this->getCourseIds()) {
                $this->_productCollection = new Varien_Data_Collection();
            } else {
                $this->_productCollection = Mage::getResourceModel('tgc_dl/course_collection')
                    ->setStoreId($this->getStoreId())
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('course_id', array('in' => $this->getCourseIds()))
                    ->addMinimalPrice()
                    ->addFinalPrice()
                    ->addTaxPercents();

                /** @var $resource Tgc_Datamart_Model_Resource_EmailLanding */
                $resource = Mage::getResourceModel('tgc_datamart/emailLanding');
                $resource->addSortOrderToCollection($this->_productCollection, $this->getLandingPageCategory());
                $this->_productCollection->getSelect()->order('landing_position', Varien_Data_Collection_Db::SORT_ORDER_ASC);

                Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this->_productCollection);
                Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($this->_productCollection);
            }
        }

        return $this->_productCollection;
    }

    /**
     * Course ids getter
     *
     * @return array
     */
    public function getCourseIds()
    {
        if (!$this->hasData('course_ids')) {
            $this->setCourseIds(
                Mage::getResourceModel('tgc_datamart/emailLanding')->getCourseIdsByCategory(
                    $this->getLandingPageCategory(),
                    Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE
                )
            );
        }

        return $this->getData('course_ids');
    }

    /**
     * Landing page category getter
     *
     * @return string
     */
    public function getLandingPageCategory()
    {
        if (!$this->hasData('landing_page_category')) {
            $this->setLandingPageCategory(Mage::registry('landing_page_category'));
        }

        return $this->getData('landing_page_category');
    }

    /**
     * Retrieve loaded product collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    /**
     * Retrieve array of available media_format options
     *
     * @param type $productConfigurableAttributes
     * @return array
     */
    public function getProductAvailableFormats(Varien_Data_Collection $productConfigurableAttributes)
    {
        $availableFormats = array();
        $mediaFormatAttributeId = Mage::getModel('eav/config')->getAttribute('catalog_product', 'media_format')
            ->getId();
        if (!$mediaFormatAttributeId) {
            return $availableFormats;
        }

        $mediaFormatConfigurableAttribute = $productConfigurableAttributes
            ->getItemByColumnValue('attribute_id', $mediaFormatAttributeId);
        if (!$mediaFormatConfigurableAttribute) {
            return $availableFormats;
        }

        $optionPricesData = $mediaFormatConfigurableAttribute->getPrices();
        if (is_array($optionPricesData) && $optionPricesData) {
            foreach ($optionPricesData as $optionPriceData) {
                $availableFormats[$optionPriceData['value_index']] = $optionPriceData['store_label'];
            }
        }

        return $availableFormats;
    }
}
