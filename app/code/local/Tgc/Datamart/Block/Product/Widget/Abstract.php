<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Product_Widget_Abstract extends Mage_Catalog_Block_Product_Widget_New
{
    const TYPE_BOTH          = 'both';
    const TYPE_GUEST         = 'guest';
    const TYPE_USER          = 'user';
    const DEFAULT_SUBJECT_ID = '900';
    const PAGE_VAR_NAME      = 'datamart-widget';
    const CACHE_TAG          = 'DATAMART_WIDGET';

    private $_customerSegment;
    private $_subjectIds;
    private $_productIdsInCart;
    private $_purchasedIds;
    protected $_collection;

    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $info = array();
        $info['customer_id']      = $this->_getCustomer()->getId();
        $info['subject_ids']      =  join(',', $this->_getSubjectIds());
        $info['user_type']        = Mage::helper('tgc_cms')->getUserType();
        $info['is_prospect']      = Mage::helper('tgc_cms')->isProspect();
        $info['customer_segment'] = $this->_getCustomerSegment();
        $info['product_ids']      = join(',', $this->_getCollectionIds());

        return array_merge(parent::getCacheKeyInfo(), $info);
    }

    protected function _getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    protected function _getCollectionIds()
    {
        $collection = $this->_getProductCollection();
        if (empty($collection) || $collection->getSize() < 1) {
            return array();
        }

        return $collection->getColumnValues('entity_id');
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
        if (!$this->_isVisibleToType($customerType)) {
            return array();
        }

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

        if ($this->getShowOnsaleOnly()) {
            //fetch a larger collection to make up for the non sale products we will strip out after collection load
            $collection->getSelect()->limit($this->_getProductsLimit() * 10);
        } else {
            $collection->getSelect()->limit($this->_getProductsLimit());
        }

        $this->_collection = $collection;

        return $this->_collection;
    }

    /**
     * Get the customer segment data
     *
     * @return mixed

     */
    protected function _getCustomerSegment()
    {
        if (isset($this->_customerSegment)) {
            return $this->_customerSegment;
        }

        $session         = Mage::getSingleton('customer/session');
        $customerSegment = $session->getCustomer()->getData('datamart_customer_pref');

        $this->_customerSegment = $customerSegment;

        return $this->_customerSegment;
    }

    /**
     * get the subject id for guest users
     *
     * @return array $subjectIds
     */
    protected function _getSubjectIds()
    {
        if (isset($this->_subjectIds)) {
            return $this->_subjectIds;
        }

        /** @var $resource Tgc_Datamart_Model_Resource_AnonymousUpsell */
        $resource  = Mage::getResourceModel('tgc_datamart/anonymousUpsell');

        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if (!$quoteId = $quote->getId()) {
            return array(self::DEFAULT_SUBJECT_ID);
        }

        $this->_productIdsInCart = (array)$this->_getProductIdsInCart();
        $subjectIds = (array)$resource->getSubjectIdsFromProductIds($this->_productIdsInCart);
        $this->_subjectIds = array_unique(array_filter($subjectIds));

        if (empty($this->_subjectIds)) {
            $this->_subjectIds = array(self::DEFAULT_SUBJECT_ID);
        }

        return $this->_subjectIds;
    }

    protected function _getProductIdsInCart()
    {
        if (isset($this->_productIdsInCart)) {
            return $this->_productIdsInCart;
        }

        /** @var $resource Tgc_Datamart_Model_Resource_AnonymousUpsell */
        $resource  = Mage::getResourceModel('tgc_datamart/anonymousUpsell');
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if (!$quote->getEntityId()) {
            return array();
        }
        $this->_productIdsInCart = (array)$resource->getProductIdsInCart($quote->getEntityId());

        return $this->_productIdsInCart;
    }

    /**
     * Filter the product collection to return guest upsell products
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getAnonymousUpsellProducts($collection)
    {
        /** @var $resource Tgc_Datamart_Model_Resource_AnonymousUpsell */
        $resource   = Mage::getResourceModel('tgc_datamart/anonymousUpsell');
        $subjectIds = $this->_getSubjectIds();
        $courseIds  = $resource->getCourseIdsBySubjectIds($subjectIds);
        if (!empty($courseIds)) {
            $collection->addAttributeToFilter('course_id', array('in' => $courseIds));
        } else {
            $collection->addAttributeToSelect('course_id');
        }
        if (!empty($courseIds)) {
            $collection = $resource->addSortOrderToCollection($collection, $subjectIds);
        } else {
            $collection->getSelect()->order(new Zend_Db_Expr('RAND()'));
        }

        return $collection;
    }

    /**
     * Filter the product collection to return customer segment upsell products
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getCustomerUpsellProducts($collection)
    {
        /** @var $resource Tgc_Datamart_Model_Resource_CustomerUpsell */
        $resource  = Mage::getResourceModel('tgc_datamart/customerUpsell');
        $customerSegment = $this->_getCustomerSegment();
        $courseIds = $resource->getCourseIdsByCustomerSegment($customerSegment);
        if (!empty($courseIds)) {
            $collection->addAttributeToFilter('course_id', array('in' => $courseIds));
        }
        if (!empty($courseIds)) {
            $collection = $resource->addSortOrderToCollection($collection, $customerSegment);
        } else {
            $collection->getSelect()->order(new Zend_Db_Expr('RAND()'));
        }

        return $collection;
    }

    /**
     * Get an array of the already purchased product ids
     */
    protected function _getPurchasedIds()
    {
        if (isset($this->_purchasedIds)) {
            return $this->_purchasedIds;
        }

        $customer = $this->_getCustomer();
        if (!$customer->getEntityId()) {
            return array();
        }
        $resource  = Mage::getResourceModel('tgc_dl/accessRights');
        $this->_purchasedIds = (array)$resource->getPurchasedProductsForCustomer($customer);

        return $this->_purchasedIds;
    }

    /**
     * Retrieve type for display
     *
     * @return string
     */
    public function getDisplayType()
    {
        if (!$this->hasData('display_type')) {
            $this->setData('display_type', self::TYPE_BOTH);
        }
        return $this->getData('display_type');
    }

    /**
     * Return whether this widget instance is visible to this customer type
     *
     * @param string $customerType
     * @return bool
     */
    protected function _isVisibleToType($customerType)
    {
        $displayType = $this->getDisplayType();

        if ($displayType == self::TYPE_BOTH) {
            return true;
        }

        return $displayType == $customerType;
    }

    protected function _getCustomerType()
    {
        if (Mage::helper('tgc_cms')->isAuthenticated()) {
            return self::TYPE_USER;
        }
        $isProspect = $this->_getIsProspect();
        if ($isProspect) {
            return self::TYPE_GUEST;
        }

        return Mage::getModel('customer/session')->isLoggedIn() ?
            self::TYPE_USER :
            self::TYPE_GUEST;
    }

    protected function _getProductsLimit()
    {
        return max(6, intval($this->getData('products_count')));
    }

    protected function _getIsProspect()
    {
        return (bool)Mage::helper('tgc_cms')->isProspect();
    }

    public function shouldShowWidget()
    {
        $displayType = $this->getDisplayType();
        if ($displayType == self::TYPE_BOTH) {
            return true;
        }

        return $displayType == $this->_getCustomerType();
    }
}
