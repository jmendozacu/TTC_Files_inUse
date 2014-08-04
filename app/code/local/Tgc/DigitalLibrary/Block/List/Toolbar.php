<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    protected $_catVarName = 'category';
    protected $_defaultCat = 'all';
    protected $_availableCats;
    private $_activeLink;

    protected function _construct()
    {
        Mage_Core_Block_Template::_construct();
        $this->_orderField = 'stream_date';
        $this->_direction = 'desc';
        if (!$this->getRequest()->getParam($this->getModeVarName())) {
            $this->setData('_current_grid_mode', "list");
        }

        $this->_availableOrder = array(
            'recent' => 'Recently Viewed',
            'date_added_asc' => 'Date Added: Ascending',
            'date_added_desc' => 'Date Added: Descending',
            'title_asc' => 'Title: A to Z',
            'title_desc' => 'Title: Z to A',
        );

        switch (Mage::getStoreConfig('catalog/frontend/list_mode')) {
            case 'grid':
                $this->_availableMode = array('grid' => $this->__('Grid'));
                break;

            case 'list':
                $this->_availableMode = array('list' => $this->__('List'));
                break;

            case 'grid-list':
                $this->_availableMode = array('grid' => $this->__('Grid'), 'list' => $this->__('List'));
                break;

            case 'list-grid':
                $this->_availableMode = array('list' => $this->__('List'), 'grid' => $this->__('Grid'));
                break;
        }

        $this->setTemplate('catalog/product/list/toolbar.phtml');
    }

    public function isActive($url)
    {
        if (empty($this->_activeLink)) {
            $this->_activeLink = $this->getAction()->getFullActionName('/');
        }

        return $this->_compareUrls($this->_completePath($url), $this->_activeLink);
    }

    protected function _compareUrls($a, $b)
    {
        $a = @end(explode('/', trim($a, '/')));
        $b = @end(explode('/', trim($b, '/')));

        return $a == $b;
    }

    protected function _completePath($path)
    {
        $path = rtrim($path, '/');
        switch (sizeof(explode('/', $path))) {
            case 1:
                $path .= '/index';
            // no break

            case 2:
                $path .= '/index';
        }
        return $path;
    }

    public function setCollection($collection)
    {
        $this->_collection = $collection;

        $this->_collection->setCurPage($this->getCurrentPage());

        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }

        if ($this->getCurrentOrder()) {
            switch ($this->getCurrentOrder()) {
                case 'recent':
                    $order = 'stream_date';
                    $dir = 'desc';
                    break;
                case 'date_added_asc':
                    $order = 'buy_date';
                    $dir = 'asc';
                    break;
                case 'date_added_desc';
                    $order = 'buy_date';
                    $dir = 'desc';
                    break;
                case 'title_asc':
                    $order = 'name';
                    $dir = 'asc';
                    break;
                case 'title_desc':
                    $order = 'name';
                    $dir = 'desc';
                    break;
                case 'downloaded':
                    $order = 'downloaded';
                    $dir = 'desc';
                    break;
            }

            if (isset($order) && isset($dir)) {
                $this->setCurrentDirection($dir);
                $this->setCurrentOrder($order);
                if ($order == 'stream_date') {
                    $this->_collection->getSelect()->order('MAX(cpr.stream_date)' . ' ' . $dir);
                } else if ($order == 'buy_date') {
                    $this->_collection->getSelect()->order('IF(access.date_purchased IS NULL, NOW(), access.date_purchased)' . ' ' . $dir);
                } else {
                    $this->_collection->setOrder($order, $dir);
                }
            }
        }

        $currentCat = $this->getCurrentCat();
        if ($currentCat && $currentCat != $this->_defaultCat) {
            $this->_collection
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                ->addAttributeToFilter('category_id', array('in' => $currentCat));
        }

        return $this;
    }

    public function getAvailableCategories()
    {
        if (isset($this->_availableCats)) {
            return $this->_availableCats;
        }

        $access = Mage::getResourceModel('tgc_dl/accessRights');
        $productIds = $access->getProductIdsFromCollection($this->_collection);
        $categoryIds = $access->getCategoryIdsFromProductIds($productIds);
        $categorySelectOptions = $access->getCategorySelectOptions($categoryIds);

        $this->_availableCats = $categorySelectOptions;

        return $this->_availableCats;
    }

    public function getCatUrl($catId)
    {
        $order = $this->getCurrentOrder();
        $direction = $this->getCurrentDirection();

        return $this->getPagerUrl(
            array(
                $this->getOrderVarName() => $order,
                //$this->getDirectionVarName() => $direction,
                $this->getPageVarName() => null,
                $this->getCatVarName() => $catId,
            )
        );
    }

    public function getOrderUrl($order, $direction)
    {
        if (is_null($order)) {
            $order = $this->getCurrentOrder() ? $this->getCurrentOrder() : $this->_availableOrder[0];
        }
        return $this->getPagerUrl(array(
            $this->getOrderVarName() => $order,
            //$this->getDirectionVarName()=>$direction,
            $this->getPageVarName() => null
        ));
    }

    public function getCurrentCat()
    {
        $cat = $this->_getData('_current_grid_cat');
        if ($cat) {
            return $cat;
        }

        $cats = $this->getAvailableCategories();
        $defaultCat = $this->_defaultCat;

        $cat = $this->getRequest()->getParam($this->getCatVarName());
        if (isset($cats[$cat])) {
            if ($cat == $defaultCat) {
                Mage::getSingleton('catalog/session')->unsFilterCat();
            } else {
                $this->_memorizeParam('filter_cat', $cat);
            }
        } else {
            $cat = Mage::getSingleton('catalog/session')->getFilterCat();
        }
        // validate session value
        if (!$cat || !isset($cats[$cat])) {
            $cat = $defaultCat;
        }
        $this->setData('_current_grid_cat', $cat);
        return $cat;
    }

    public function getCatVarName()
    {
        return $this->_catVarName;
    }

    public function isCatCurrent($catId)
    {
        return ($catId == $this->getCurrentCat());
    }

    public function getCurrentOrderKey()
    {
        foreach ($this->getAvailableOrders() as $_key => $_order) {
            if ($this->isOrderCurrent($_key)) {
                return $_key;
            }
        }
    }

    public function getPagerHtml()
    {
        $pagerBlock = $this->getChild('product_list_toolbar_pager');

        if ($pagerBlock instanceof Varien_Object) {

            /* @var $pagerBlock Mage_Page_Block_Html_Pager */
            $pagerBlock->setAvailableLimit($this->getAvailableLimit());

            $pagerBlock->setUseContainer(false)
                ->setShowPerPage(false)
                ->setShowAmounts(false)
                ->setLimitVarName($this->getLimitVarName())
                ->setPageVarName($this->getPageVarName())
                ->setLimit($this->getLimit())
                ->setFrameLength(Mage::getStoreConfig('design/pagination/pagination_frame'))
                ->setJump(Mage::getStoreConfig('design/pagination/pagination_frame_skip'))
                ->setCollection($this->getCollection())
                ->setTemplate('boutique/widget/pager.phtml');

            return $pagerBlock->toHtml();
        }

        return '';
    }
}
