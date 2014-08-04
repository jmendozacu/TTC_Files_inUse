<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Product_Abstract extends Mage_Catalog_Block_Product_List
    implements Mage_Widget_Block_Interface
{
    protected $_collection;

    protected function _construct()
    {
        parent::_construct();
        $this->setUniqueId(Mage::helper('core')->uniqHash('boutique_widget_'));
        $this->getCollection();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()
            ->createBlock('page/html_pager', 'boutique.pager')
            ->setCollection($this->getCollection())
            ->setTemplate('boutique/widget/pager.phtml');

        $this->setChild('pager', $pager);
        $this->getCollection();

        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    protected function _beforeToHtml()
    {
        $this->setCollection($this->getCollection());
        return parent::_beforeToHtml();
    }

    protected function _getProductIds()
    {
        $ids = $this->getData('product_ids');
        if ($ids) {
            $ids = explode('}{', $ids);
            $cleanIds = array();

            foreach ($ids as $id) {
                $id = str_replace('{', '', $id);
                $id = str_replace('}', '', $id);
                $cleanIds[] = $id;
            }

            return $cleanIds;
        }

        return array();
    }

    protected function _addProductFilter()
    {
        $idsToFilter = $this->_getProductIds();
        if (empty($idsToFilter)) {
            return;
        }

        $this->_collection->addAttributeToFilter('entity_id', array('in' => $idsToFilter));
    }

    protected function _getCatToFilter()
    {
        $cat = $this->getData('category');
        if (!empty($cat)) {
            $parts = explode('/', $cat);

            return isset($parts[1]) ? $parts[1] : false;
        }

        return false;
    }

    protected function _addCategoryFilter()
    {
        $catToFilter = $this->_getCatToFilter();
        if (empty($catToFilter)) {
            return;
        }

        $this->_collection
            ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
            ->addAttributeToFilter('category_id', array('in' => $catToFilter));
    }

    protected function _getLimit()
    {
        $limit = $this->getData('products_count');

        return intval($limit) > 0 ? intval($limit) : false;
    }

    protected function _applyLimit()
    {
        $limit = $this->_getLimit();
        if (empty($limit)) {
            return;
        }

        $this->_collection->getSelect()->limit($limit);
    }

    protected function _getPriceBelowToFilter()
    {
        $priceBelow = $this->getData('price_below');

        return $priceBelow ? $priceBelow : false;
    }

    protected function _addPriceBelowFilter()
    {
        $priceBelow = $this->_getPriceBelowToFilter();
        if (empty($priceBelow) || !is_numeric($priceBelow)) {
            return;
        }

        $this->_collection->getSelect()->where('price_index.min_price <= ?', $priceBelow);
    }

    protected function _getPercentOffToFilter()
    {
        $percentOff = $this->getData('percent_off');

        return $percentOff ? $percentOff : false;
    }

    protected function _addPercentOffFilter()
    {
        $percentOff = $this->_getPercentOffToFilter();
        if (empty($percentOff) || !is_numeric($percentOff)) {
            return;
        }

        $decimalValue = $percentOff * 0.01;
        $checkValue = $decimalValue + 1;

        $this->_collection->getSelect()->where('(price_index.price / price_index.final_price) >= ?', $checkValue);
    }

    public function getCollection()
    {
        if (isset($this->_collection)) {
            return $this->_collection;
        }

        $this->_collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addAttributeToFilter('type_id', array('eq' => 'configurable'));

        $this->_addProductFilter();
        $this->_addCategoryFilter();
        $this->_addPriceBelowFilter();
        $this->_addPercentOffFilter();
        $this->_applyLimit();

        $ids = $this->_collection->getColumnValues('entity_id');
        $this->_collection = null;

        $this->_collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addUrlRewrite()
            ->addAttributeToFilter('type_id', array('eq' => 'configurable'))
            ->addAttributeToFilter('entity_id', array('in' => $ids));
        $this->_applyLimit();

        return $this->_collection;
    }

    public function getCount()
    {
        $collection = $this->getCollection();

        return min(count($collection->getAllIds()), $this->getData('products_count'));
    }
}
