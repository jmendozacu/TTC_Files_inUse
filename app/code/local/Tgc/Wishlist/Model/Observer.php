<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Wishlist_Model_Observer
{
    /**
     * Flag that indicates that wishlist items options update is running
     */
    protected $_updatingWishlistItemsOptions = false;

    /**
     * Currently updated item
     *
     * @var Mage_Wishlist_Model_Item
     */
    protected $_updatedItem;

    /**
     * Retrieve wishlist object
     * @param int $wishlistId
     * @return Mage_Wishlist_Model_Wishlist|bool
     */
    protected function _getWishlist($wishlistId = null)
    {
        $wishlist = Mage::registry('wishlist');
        if ($wishlist) {
            return $wishlist;
        }

        try {
            if (!$wishlistId) {
                $wishlistId = Mage::app()->getRequest()->getParam('wishlist_id');
            }
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            /* @var Mage_Wishlist_Model_Wishlist $wishlist */
            $wishlist = Mage::getModel('wishlist/wishlist');
            if ($wishlistId) {
                $wishlist->load($wishlistId);
            } else {
                $wishlist->loadByCustomer($customerId, true);
            }

            if (!$wishlist->getId() || $wishlist->getCustomerId() != $customerId) {
                return false;
            }

            Mage::register('wishlist', $wishlist);
        } catch (Exception $e) {
            return false;
        }

        return $wishlist;
    }

    /**
     * Save wishlist items options
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Wishlist_Model_Observer
     */
    public function updateWishlistItemsOptions(Varien_Event_Observer $observer)
    {
        $controllerAction = $observer->getEvent()->getControllerAction();
        $wishlist = $this->_getWishlist();
        $itemsOptions = $controllerAction->getRequest()->getParam('item_options');
        if (!$itemsOptions || !is_array($itemsOptions)) {
            $itemsOptions = array();
        }

        if ($wishlist && $itemsOptions) {
            $this->_updatingWishlistItemsOptions = true;

            $wishlist = clone $wishlist;
            $itemsCollection = $wishlist->getItemCollection()
                ->setVisibilityFilter();
            $itemsCollection->addFieldToFilter(
                $itemsCollection->getResource()->getIdFieldName(),
                array('in', array_keys($itemsOptions))
            );

            $shouldSaveWishlist = false;
            foreach ($itemsCollection as $item) {
                $hasUpdates = false;
                $buyRequest = $item->getBuyRequest()->getData();
                foreach ($itemsOptions[$item->getId()] as $key => $option) {
                    if (!isset($buyRequest[$key]) || $buyRequest[$key] !== $option) {
                        $hasUpdates = true;
                        break;
                    }
                }

                if ($hasUpdates) {
                    $this->_updatedItem = null;

                    $item->mergeBuyRequest($itemsOptions[$item->getId()]);
                    $wishlist->updateItem($item, $item->getBuyRequest());

                    $this->_updateRequestAfterItemUpdate($item, $this->_updatedItem);

                    $shouldSaveWishlist = true;
                }
            }

            if ($shouldSaveWishlist) {
                $wishlist->save();
            }

            $this->_updatingWishlistItemsOptions = false;
        }

        return $this;
    }

    /**
     * Update request params after item is updated
     *
     * @param Mage_Wishlist_Model_Item $oldItem
     * @param Mage_Wishlist_Model_Item $newItem
     * @return \Tgc_Wishlist_Model_Observer
     */
    protected function _updateRequestAfterItemUpdate($oldItem, $newItem)
    {
        $request = Mage::app()->getRequest();

        // updating request params if item update resulted in new item
        if ($newItem && $newItem->getId() && $newItem->getId() != $oldItem->getId()) {
            // updating selected items request param
            if (($selectedWishlistItems = $request->getParam('wishlist_item')) && is_array($selectedWishlistItems)) {
                $updateRequired = false;
                foreach ($selectedWishlistItems as $key => $selectedItemId) {
                    if ($selectedItemId == $oldItem->getId()) {
                        unset($selectedWishlistItems[$key]);
                        $selectedWishlistItems[] = $newItem->getId();
                        $updateRequired = true;
                    }
                }
                if ($updateRequired) {
                    $request->setParam('wishlist_item', $selectedWishlistItems);
                    if ($request->getPost('wishlist_item')) {
                        $request->setPost('wishlist_item', $selectedWishlistItems);
                    }
                }
            }

            // updating qty request param
            if (($qtys = $request->getParam('qty')) && is_array($qtys)) {
                if (isset($qtys[$oldItem->getId()])) {
                    $qtys[$newItem->getId()] = (isset($qtys[$newItem->getId()]) ? $qtys[$newItem->getId()] : 0)
                        + $qtys[$oldItem->getId()];
                    unset($qtys[$oldItem->getId()]);
                    $request->setParam('qty', $qtys);
                    if ($request->getPost('qty')) {
                        $request->setPost('qty', $qtys);
                    }
                }
            }

            // updating item request param
            if (($itemId = $request->getParam('item')) && $itemId == $oldItem->getId()) {
                $request->setParam('item', $newItem->getId());
            }
        }

        return $this;
    }

    /**
     * Catch updated item object for internal use
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_Wishlist_Model_Observer
     */
    public function catchUpdatedItem(Varien_Event_Observer $observer)
    {
        if ($this->_updatingWishlistItemsOptions) {
            $items = $observer->getEvent()->getItems();
            if (count($items)) {
                $this->_updatedItem = array_shift($items);
            }
        }
        return $this;
    }

    public function changeAfterAuthUrl(Varien_Event_Observer $observer)
    {
        $event      = $observer->getEvent();
        $controller = $event->getController();
        $action     = Mage::app()->getRequest()->getActionName();
        $session    = $event->getSession();
        $result     = $event->getResult();

        if ($controller instanceof Enterprise_Wishlist_IndexController
            && $action == 'index'
            && !$result) {

            $session->setBeforeAuthUrl(Mage::getUrl('account/wishlist'));
        }
    }
}
