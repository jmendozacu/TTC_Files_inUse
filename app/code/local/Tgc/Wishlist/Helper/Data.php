<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Wishlist_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Sort wishlist items
     *
     * @param Mage_Wishlist_Model_Resource_Item_Collection|array $items
     * @param string $sortField
     * @param string $sortDirection
     * @return array
     */
    public function sortWishlistItems($items, $sortField, $sortDirection = 'asc')
    {
        $itemsAsArray = $items;
        if ($itemsAsArray instanceof Mage_Wishlist_Model_Resource_Item_Collection) {
            $itemsAsArray = $itemsAsArray->getItems();
        }

        switch ($sortField) {
            case 'date':
                $this->_sortWishlistItemsByDate($itemsAsArray, $sortDirection);
                break;
            case 'price':
                $this->_sortWishlistItemsByPrice($itemsAsArray, $sortDirection);
                break;
        }
        return $itemsAsArray;
    }

    /**
     * Sort wishlist items by date added
     *
     * @param Mage_Wishlist_Model_Resource_Item_Collection|array $items
     * @param string $sortDirection
     * @return array
     */
    protected function _sortWishlistItemsByDate(&$items, $sortDirection)
    {
        uasort($items, array(
            $this,
            ($sortDirection == 'asc' ? '_compareWishlistItemsByDateAsc' : '_compareWishlistItemsByDateDesc')
        ));

        return $items;
    }

    /**
     * Compare wishlist items by date
     *
     * @param Varien_Object $a
     * @param Varien_Object $b
     * @param string $direction
     * @return int
     */
    protected function _compareWishlistItemsByDate($a, $b, $direction)
    {
        $aAddedAt = strtotime($a->getAddedAt());
        $bAddedAt = strtotime($b->getAddedAt());
        if ($aAddedAt == $bAddedAt) {
            return 0;
        }

        return $direction == 'asc' ? ($aAddedAt < $bAddedAt ? -1 : 1) : ($aAddedAt > $bAddedAt ? -1 : 1);
    }

    /**
     * Compare wishlist items by date callback
     *
     * @param Varien_Object $a
     * @param Varien_Object $b
     * @return int
     */
    protected function _compareWishlistItemsByDateAsc($a, $b)
    {
        return $this->_compareWishlistItemsByDate($a, $b, 'asc');
    }

    /**
     * Compare wishlist items by date callback
     *
     * @param Varien_Object $a
     * @param Varien_Object $b
     * @return int
     */
    protected function _compareWishlistItemsByDateDesc($a, $b)
    {
        return $this->_compareWishlistItemsByDate($a, $b, 'desc');
    }

    /**
     * Sort wishlist items by price
     *
     * @param Mage_Wishlist_Model_Resource_Item_Collection|array $items
     * @param string $sortDirection
     * @return array
     */
    protected function _sortWishlistItemsByPrice(&$items, $sortDirection)
    {
        uasort($items, array(
            $this,
            ($sortDirection == 'asc' ? '_compareWishlistItemsByPriceAsc' : '_compareWishlistItemsByPriceDesc')
        ));
        return $items;
    }

    /**
     * Compare wishlist items by price
     *
     * @param Varien_Object $a
     * @param Varien_Object $b
     * @param string $direction
     * @return int
     */
    protected function _compareWishlistItemsByPrice($a, $b, $direction)
    {
        $aPrice = $this->_getItemFinalPrice($a);
        $bPrice = $this->_getItemFinalPrice($b);
        if ($aPrice == $bPrice) {
            return 0;
        }

        return $direction == 'asc' ? ($aPrice < $bPrice ? -1 : 1) : ($aPrice > $bPrice ? -1 : 1);
    }

    /**
     * Compare wishlist items by price callback
     *
     * @param Varien_Object $a
     * @param Varien_Object $b
     * @return int
     */
    protected function _compareWishlistItemsByPriceAsc($a, $b)
    {
        return $this->_compareWishlistItemsByPrice($a, $b, 'asc');
    }

    /**
     * Compare wishlist items by price callback
     *
     * @param Varien_Object $a
     * @param Varien_Object $b
     * @return int
     */
    protected function _compareWishlistItemsByPriceDesc($a, $b)
    {
        return $this->_compareWishlistItemsByPrice($a, $b, 'desc');
    }

    public function getAddToCartUrl($item)
    {
        $continueUrl = Mage::helper('core')->urlEncode(
            $this->_getUrl('*/*/*', array(
                '_current' => true,
                '_use_rewrite' => true,
                '_store_to_url' => true,
            ))
        );
        $params = array(
            'item' => is_string($item) ? $item : $item->getWishlistItemId(),
            Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $continueUrl,
            Mage_Core_Model_Url::FORM_KEY => Mage::getSingleton('core/session')->getFormKey()
        );
        return $this->_getUrlStore($item)->getUrl('tgc_wishlist/index/cart', $params);
    }

    protected function _getUrlStore($item)
    {
        $storeId = null;
        $product = null;
        if ($item instanceof Mage_Wishlist_Model_Item) {
            $product = $item->getProduct();
        } elseif ($item instanceof Mage_Catalog_Model_Product) {
            $product = $item;
        }
        if ($product) {
            if ($product->isVisibleInSiteVisibility()) {
                $storeId = $product->getStoreId();
            } else if ($product->hasUrlDataObject()) {
                $storeId = $product->getUrlDataObject()->getStoreId();
            }
        }
        return Mage::app()->getStore($storeId);
    }

    /**
     * Compose item final price
     *
     * @param Varien_Object $item
     */
    protected function _getItemFinalPrice($item)
    {
        $product = $item->getProduct();
        $finalPrice = $product->getFinalPrice();
        if ($product->isConfigurable()) {
            if (!$product->hasSimpleFinalPrice()) {
                $simpleProductId = $item->getOptionByCode('simple_product');
                $simpleProduct = Mage::getModel('catalog/product');
                if ($simpleProductId) {
                    $simpleProductId = $simpleProductId->getValue();
                    $simpleProduct = $simpleProduct->load($simpleProductId);
                }
                if ($simpleProduct->getId()) {
                    $finalPrice = $simpleProduct->getFinalPrice();

                    if ($product->getCustomerGroupId()) {
                        $finalPrice = $simpleProduct->getGroupPrice();
                    }
                } else {
                    if (Mage::getStoreConfig('spp/setting/showfromprice')) {
                        $childProductPrices = $product->getPriceModel()->getChildProductPrices($product);
                        $finalPrice = $childProductPrices['Min'];
                    }
                }
                $product->setSimpleFinalPrice($finalPrice);
            }

            $finalPrice = $product->getSimpleFinalPrice();
        }
        return $finalPrice;
    }

    /**
     * Retrieve url for adding product to wishlist
     *
     * @param Mage_Catalog_Model_Product|Mage_Wishlist_Model_Item $item
     *
     * @return  string|bool
     */
    public function getAddUrl($item)
    {
        return $this->getAddUrlWithParams($item);
    }

    /**
     * Retrieve url for adding product to wishlist with params
     *
     * @param Mage_Catalog_Model_Product|Mage_Wishlist_Model_Item $item
     * @param array $params
     *
     * @return  string|bool
     */
    public function getAddUrlWithParams($item, array $params = array())
    {
        $productId = null;
        if ($item instanceof Mage_Catalog_Model_Product) {
            $productId = $item->getEntityId();
        }
        if ($item instanceof Mage_Wishlist_Model_Item) {
            $productId = $item->getProductId();
        }

        if ($productId) {
            $params['product'] = $productId;
            $params[Mage_Core_Model_Url::FORM_KEY] = $this->_getSingletonModel('core/session')->getFormKey();
            return $this->_getUrlStore($item)->getUrl('tgc_wishlist/index/add', $params);
        }

        return false;
    }

    /**
     * Return model instance
     *
     * @param string $className
     * @param array $arguments
     * @return Mage_Core_Model_Abstract
     */
    protected function _getSingletonModel($className, $arguments = array())
    {
        return Mage::getSingleton($className, $arguments);
    }
}
