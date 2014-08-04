<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Product_Widget_PdpRecommended extends Tgc_Datamart_Block_Product_Widget_Abstract
implements Mage_Widget_Block_Interface
{
    const PAGE_VAR_NAME      = 'datamart-pdp-widget';

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('datamart/product/widget/upsell/pdp_recommended.phtml');
        }

        $this->addPriceBlockType('bundle', 'bundle/catalog_product_price', 'bundle/catalog/product/price.phtml');
        // disabling caching because this block content contains form with form_key in action URL
        // and form key is session specific
        // since this block is rendered only on PDP full page content will be cached by FPC
        $this->unsCacheLifetime();
    }

    /**
     * Product collection initialize process
     *
     * @return array | Mage_Catalog_Model_Resource_Product_Collection|Object|Varien_Data_Collection
     */
    protected function _getProductCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $customerType = $this->_getCustomerType();

        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addAttributetoSelect('inline_rating')
            ->addUrlRewrite();

        $purchasedIds = $this->_getPurchasedIds();
        if (!empty($purchasedIds)) {
            $collection->addAttributeToFilter('entity_id', array('nin' => $purchasedIds));
        }

        $idsInCart = $this->_getProductIdsInCart();
        if (Mage::registry('current_product') && Mage::registry('current_product')->getId()) {
            $idsInCart[] = Mage::registry('current_product')->getId();
        }
        if (!empty($idsInCart)) {
            $collection->addAttributeToFilter('entity_id', array('nin' => $idsInCart));
        }

        $this->_getCustomerSegment();
        if ($customerType == self::TYPE_GUEST || empty($this->_customerSegment) || !Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection = $this->_getAnonymousUpsellProducts($collection);
        } else {
            $collection = $this->_getCustomerUpsellProducts($collection);
        }

        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        $collection->setOrder('position', 'asc');
        $collection->getSelect()->limit(1);

        $this->_collection = $collection;

        return $this->_collection;
    }
}
