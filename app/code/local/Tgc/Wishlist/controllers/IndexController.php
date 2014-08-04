<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Wishlist_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * If true, authentication in this controller (wishlist) could be skipped
     *
     * @var bool
     */
    protected $_skipAuthentication = false;

    /**
     * Filter to convert localized values to internal ones
     * @var Zend_Filter_LocalizedToNormalized
     */
    protected $_localFilter = null;

    /** @var int */
    protected $_digitalProductQty = 1;

    /**
     * Extend preDispatch
     *
     * @return Mage_Core_Controller_Front_Action|void
     */
    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->_skipAuthentication && !Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
            if (!Mage::getSingleton('customer/session')->getBeforeWishlistUrl()) {
                Mage::getSingleton('customer/session')->setBeforeWishlistUrl($this->_getRefererUrl());
            }
            Mage::getSingleton('customer/session')->setBeforeWishlistRequest($this->getRequest()->getParams());
        }
        if (!Mage::getStoreConfigFlag('wishlist/general/active')) {
            $this->norouteAction();
            return;
        }
    }

    /**
     * Set skipping authentication in actions of this controller (wishlist)
     *
     * @return Mage_Wishlist_IndexController
     */
    public function skipAuthentication()
    {
        $this->_skipAuthentication = true;
        return $this;
    }

    /**
     * Processes localized qty (entered by user at frontend) into internal php format
     *
     * @param string $qty
     * @return float|int|null
     */
    protected function _processLocalizedQty($qty)
    {
        if (!$this->_localFilter) {
            $this->_localFilter = new Zend_Filter_LocalizedToNormalized(
                array('locale' => Mage::app()->getLocale()->getLocaleCode())
            );
        }
        $qty = $this->_localFilter->filter((float)$qty);
        if ($qty < 0) {
            $qty = null;
        }
        return $qty;
    }

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
                $wishlistId = $this->getRequest()->getParam('wishlist_id');
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
                $wishlist = null;
                Mage::throwException(
                    Mage::helper('wishlist')->__("Requested wishlist doesn't exist")
                );
            }

            Mage::register('wishlist', $wishlist);
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('wishlist/session')->addError($e->getMessage());
            return false;
        } catch (Exception $e) {
            Mage::getSingleton('wishlist/session')->addException($e,
                Mage::helper('wishlist')->__('Wishlist could not be created.')
            );
            return false;
        }

        return $wishlist;
    }

    /**
     * Delete multiple items action
     *
     * @return Tgc_Wishlist_IndexController
     */
    public function deleteItemsAction()
    {
        $itemsToDelete = $this->getRequest()->getParam('wishlist_item', array());
        if (!$itemsToDelete || !is_array($itemsToDelete)) {
            $this->_redirectReferer(Mage::getUrl('wishlist'));
            return $this;
        }

        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            return $this->norouteAction();
        }

        $itemsCollection = $wishlist->getItemCollection();
        $itemsCollection->addFieldToFilter(
            $itemsCollection->getResource()->getIdFieldName(),
            array('in' => $itemsToDelete)
        );

        try {
            foreach ($itemsCollection as $item) {
                $item->delete();
            }
            $wishlist->save();
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('customer/session')->addError(
                Mage::helper('wishlist')->__('An error occurred while deleting the item from wishlist: %s', $e->getMessage())
            );
        } catch (Exception $e) {
            Mage::getSingleton('customer/session')->addError(
                Mage::helper('wishlist')->__('An error occurred while deleting the item from wishlist.')
            );
        }

        Mage::helper('wishlist')->calculate();

        $this->_redirectReferer(Mage::getUrl('wishlist'));
    }

    /**
     * Add selected wishlist items to cart
     */
    public function toCartItemsAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_forward('noRoute');
            return;
        }

        $itemsToAdd = $this->getRequest()->getParam('wishlist_item', array());
        if (!$itemsToAdd || !is_array($itemsToAdd)) {
            $this->_redirectReferer(Mage::getUrl('wishlist'));
            return;
        }

        $wishlist = $this->_getWishlist();
        if (!$wishlist) {
            $this->_forward('noRoute');
            return;
        }
        $isOwner = $wishlist->isOwner(Mage::getSingleton('customer/session')->getCustomerId());

        $messages = array();
        $addedItems = array();
        $notSalable = array();
        $hasOptions = array();
        $digitalItem = array();

        $cart = Mage::getSingleton('checkout/cart');
        $collection = $wishlist->getItemCollection()
            ->setVisibilityFilter();
        $collection->addFieldToFilter($collection->getResource()->getIdFieldName(), array('in', $itemsToAdd));

        $qtys = $this->getRequest()->getParam('qty');
        if (!$qtys || !is_array($qtys)) {
            $qtys = array();
        }

        $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

        foreach ($collection as $item) {
            /** @var item Mage_Wishlist_Model_Item */
            try {
                $disableAddToCart = $item->getProduct()->getDisableAddToCart();
                $item->unsProduct();

                // Set qty
                if (isset($qtys[$item->getId()])) {
                    $qty = $this->_processLocalizedQty($qtys[$item->getId()]);
                    if ($qty) {
                        $item->setQty($qty);
                    }
                }
                $item->getProduct()->setDisableAddToCart($disableAddToCart);

                $option = $item->getOptionByCode('info_buyRequest');
                $initialData = $option ? unserialize($option->getValue()) : null;

                if (in_array($initialData['media-format-radio'], $mediaAttributeIds) && array_key_exists('super_attribute', $initialData)) {
                    $productCollection = Mage::getModel('catalog/product_type_configurable')
                        ->getUsedProductCollection($item->getProduct());
                    $attributesInfo = $initialData['super_attribute'];
                    foreach ($attributesInfo as $attributeId => $attributeValue) {
                        $productCollection->addAttributeToFilter($attributeId, $attributeValue);
                    }
                    $productObject = $productCollection->getFirstItem();

                    if ($cart->getQuote()->hasProductId($productObject->getId())) {
                        $digitalItem[] = $item;
                        $item->delete();
                        continue;
                    } else {
                        $item->setQty($this->_digitalProductQty);
                    }
                }

                // Add to cart
                if ($item->addToCart($cart, $isOwner)) {
                    $addedItems[] = $item->getProduct();
                }

            } catch (Mage_Core_Exception $e) {
                if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                    $notSalable[] = $item;
                } else if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_HAS_REQUIRED_OPTIONS) {
                    $hasOptions[] = $item;
                } else {
                    $messages[] = Mage::helper('wishlist')->__('%s for "%s".', trim($e->getMessage(), '.'), $item->getProduct()->getName());
                }

                $cartItem = $cart->getQuote()->getItemByProduct($item->getProduct());
                if ($cartItem) {
                    $cart->getQuote()->deleteItem($cartItem);
                }
            } catch (Exception $e) {
                Mage::logException($e);
                $messages[] = Mage::helper('wishlist')->__('Cannot add the item to shopping cart.');
            }
        }

        if ($isOwner) {
            $indexUrl = Mage::helper('wishlist')->getListUrl($wishlist->getId());
        } else {
            $indexUrl = Mage::getUrl('wishlist/shared', array('code' => $wishlist->getSharingCode()));
        }
        if (Mage::helper('checkout/cart')->getShouldRedirectToCart()) {
            $redirectUrl = Mage::helper('checkout/cart')->getCartUrl();
        } else if ($this->_getRefererUrl()) {
            $redirectUrl = $this->_getRefererUrl();
        } else {
            $redirectUrl = $indexUrl;
        }

        if ($notSalable) {
            $products = array();
            foreach ($notSalable as $item) {
                $products[] = '"' . $item->getProduct()->getName() . '"';
            }
            $messages[] = Mage::helper('wishlist')->__('Unable to add the following product(s) to shopping cart: %s.', join(', ', $products));
        }

        if ($hasOptions) {
            $products = array();
            foreach ($hasOptions as $item) {
                $products[] = '"' . $item->getProduct()->getName() . '"';
            }
            $messages[] = Mage::helper('wishlist')->__('Product(s) %s have required options. Each of them can be added to cart separately only.', join(', ', $products));
        }

        if ($messages) {
            $isMessageSole = (count($messages) == 1);
            if ($isMessageSole && count($hasOptions) == 1) {
                $item = $hasOptions[0];
                if ($isOwner) {
                    $item->delete();
                }
                $redirectUrl = $item->getProductUrl();
            } else {
                $wishlistSession = Mage::getSingleton('wishlist/session');
                foreach ($messages as $message) {
                    $wishlistSession->addError($message);
                }
                $redirectUrl = $indexUrl;
            }
        }

        if ($addedItems) {
            // save wishlist model for setting date of last update
            try {
                $wishlist->save();
            } catch (Exception $e) {
                Mage::getSingleton('wishlist/session')->addError(Mage::helper('wishlist')->__('Cannot update wishlist'));
                $redirectUrl = $indexUrl;
            }

            $products = array();
            foreach ($addedItems as $product) {
                $products[] = '"' . $product->getName() . '"';
            }

            Mage::getSingleton('checkout/session')->addSuccess(
                Mage::helper('wishlist')->__('%d product(s) have been added to shopping cart: %s.', count($addedItems), join(', ', $products))
            );

            // save cart and collect totals
            $cart->save()->getQuote()->collectTotals();
        }

        if ($digitalItem) {
            // save wishlist model for setting date of last update
            try {
                $wishlist->save();
            } catch (Exception $e) {
                Mage::getSingleton('wishlist/session')->addError(Mage::helper('wishlist')->__('Cannot update wishlist'));
                $redirectUrl = $indexUrl;
            }

            Mage::getSingleton('checkout/session')->addNotice(
                Mage::helper('wishlist')->__('One or more items were not added to your cart,<br />because a downloadable product can only be added to your cart once.')
            );

            // save cart and collect totals
            $cart->save()->getQuote()->collectTotals();
        }

        Mage::helper('wishlist')->calculate();

        $this->_redirectUrl($redirectUrl);
    }

    public function cartAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('wishlist/index');
//            $this->_redirect('*/*');
        }
        $itemId = (int)$this->getRequest()->getParam('item');

        /* @var $item Mage_Wishlist_Model_Item */
        $item = Mage::getModel('wishlist/item')->load($itemId);
        if (!$item->getId()) {
            return $this->_redirect('wishlist/index');
        }
        $wishlist = $this->_getWishlist($item->getWishlistId());
        if (!$wishlist) {
            return $this->_redirect('wishlist/index');
        }

        // Set qty
        $qty = $this->getRequest()->getParam('qty');
        if (is_array($qty)) {
            if (isset($qty[$itemId])) {
                $qty = $qty[$itemId];
            } else {
                $qty = 1;
            }
        }
        $qty = $this->_processLocalizedQty($qty);
        if ($qty) {
            $item->setQty($qty);
        }

        /* @var $session Mage_Wishlist_Model_Session */
        $session = Mage::getSingleton('wishlist/session');
        $cart = Mage::getSingleton('checkout/cart');

        $redirectUrl = Mage::getUrl('wishlist/index');
        $invalidDigitalItem = false;
        try {
            $options = Mage::getModel('wishlist/item_option')->getCollection()
                ->addItemFilter(array($itemId));
            $item->setOptions($options->getOptionsByItem($itemId));

            $buyRequest = Mage::helper('catalog/product')->addParamsToBuyRequest(
                $this->getRequest()->getParams(),
                array('current_config' => $item->getBuyRequest())
            );

            $item->mergeBuyRequest($buyRequest);

            $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

            $option = $item->getOptionByCode('info_buyRequest');
            $initialData = $option ? unserialize($option->getValue()) : null;

            if (in_array($initialData['media-format-radio'], $mediaAttributeIds) && array_key_exists('super_attribute', $initialData)) {
                $productCollection = Mage::getModel('catalog/product_type_configurable')
                    ->getUsedProductCollection($item->getProduct());
                $attributesInfo = $initialData['super_attribute'];
                foreach ($attributesInfo as $attributeId => $attributeValue) {
                    $productCollection->addAttributeToFilter($attributeId, $attributeValue);
                }
                $productObject = $productCollection->getFirstItem();

                if ($cart->getQuote()->hasProductId($productObject->getId())) {
                    $invalidDigitalItem = true;
                    $session->addNotice(
                        Mage::helper('wishlist')->__('This item is already in your cart and could not be added again,<br />because a downloadable product can only be added to your cart once.')
                    );
                    $item->delete();
                } else {
                    $item->setQty($this->_digitalProductQty);
                }
            }
            if (!$invalidDigitalItem) {
                if ($item->addToCart($cart, true)) {
                    $cart->save();
                    Mage::getSingleton('checkout/session')->addSuccess(
                        Mage::helper('wishlist')->__('This product has been added to shopping cart: %s.', $item->getProduct()->getName())
                    );
                }
            }

            $cart->getQuote()->collectTotals()->save();

            $wishlist->save();
            Mage::helper('wishlist')->calculate();

            if (Mage::helper('checkout/cart')->getShouldRedirectToCart()) {
                $redirectUrl = Mage::helper('checkout/cart')->getCartUrl();
            } else if ($this->_getRefererUrl()) {
                $redirectUrl = $this->_getRefererUrl();
            }
            Mage::helper('wishlist')->calculate();
        } catch (Mage_Core_Exception $e) {
            if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                $session->addError($this->__('This product(s) is currently out of stock'));
            } else if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_HAS_REQUIRED_OPTIONS) {
                Mage::getSingleton('core/session')->addNotice($e->getMessage());
                $redirectUrl = Mage::getUrl('wishlist/index/configure/', array('id' => $item->getId()));
            } else {
                Mage::getSingleton('core/session')->addNotice($e->getMessage());
                $redirectUrl = Mage::getUrl('wishlist/index/configure/', array('id' => $item->getId()));
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $session->addException($e, $this->__('Cannot add item to shopping cart'));
        }

        Mage::helper('wishlist')->calculate();

        return $this->_redirectUrl($redirectUrl);
    }

    /**
     * Adding new item
     *
     * @return Mage_Core_Controller_Varien_Action|void
     */
    public function addAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*');
        }
        $this->_addItemToWishList();
    }

    /**
     * Add the item to wish list
     *
     * @return Mage_Core_Controller_Varien_Action|void
     */
    protected function _addItemToWishList()
    {
        if (!$this->getRequest()->isAjax()) {
            $wishlist = $this->_getWishlist();
            if (!$wishlist) {
                return $this->norouteAction();
            }

            $session = Mage::getSingleton('customer/session');

            $productId = (int)$this->getRequest()->getParam('product');
            if (!$productId) {
                $this->_redirect('*/');
                return;
            }

            $product = Mage::getModel('catalog/product')->load($productId);
            if (!$product->getId() || !$product->isVisibleInCatalog()) {
                $session->addError($this->__('Cannot specify product.'));
                $this->_redirect('*/');
                return;
            }

            try {
                $requestParams = $this->getRequest()->getParams();
                if ($session->getBeforeWishlistRequest()) {
                    $requestParams = $session->getBeforeWishlistRequest();
                    $session->unsBeforeWishlistRequest();
                }
                $buyRequest = new Varien_Object($requestParams);

                $result = $wishlist->addNewItem($product, $buyRequest);
                if (is_string($result)) {
                    Mage::throwException($result);
                }
                $wishlist->save();

                Mage::dispatchEvent(
                    'wishlist_add_product',
                    array(
                        'wishlist' => $wishlist,
                        'product' => $product,
                        'item' => $result
                    )
                );

                $referer = $session->getBeforeWishlistUrl();
                if ($referer) {
                    $session->setBeforeWishlistUrl(null);
                } else {
                    $referer = $this->_getRefererUrl();
                }

                /**
                 *  Set referer to avoid referring to the compare popup window
                 */
                $session->setAddActionReferer($referer);

                Mage::helper('wishlist')->calculate();

                $message = $this->__('%1$s has been added to your wishlist. Click <a href="%2$s">here</a> to continue shopping.',
                    $product->getName(), Mage::helper('core')->escapeUrl($referer));
                $session->addSuccess($message);
            } catch (Mage_Core_Exception $e) {
                $session->addError($this->__('An error occurred while adding item to wishlist: %s', $e->getMessage()));
            }
            catch (Exception $e) {
                $session->addError($this->__('An error occurred while adding item to wishlist.'));
            }

            $this->_redirect('*', array('wishlist_id' => $wishlist->getId()));
        } else {
            $wishlist = $this->_getWishlist();
            $response = array();
            if (!$wishlist) {
                $response['messages'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
                $this->_sendAjaxResponse($response);
                return;
            }

            $session = Mage::getSingleton('core/session');

            $productId = (int)$this->getRequest()->getParam('product');
            if (!$productId) {
                $response['messages'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
                $this->_sendAjaxResponse($response);
                return;
            }

            $product = Mage::getModel('catalog/product')->load($productId);
            if (!$product->getId() || !$product->isVisibleInCatalog()) {
                $session->addError($this->__('Cannot specify product.'));
                $response['messages'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
                $this->_sendAjaxResponse($response);
                return;
            }

            try {
                $requestParams = $this->getRequest()->getParams();
                if ($session->getBeforeWishlistRequest()) {
                    $requestParams = $session->getBeforeWishlistRequest();
                    $session->unsBeforeWishlistRequest();
                }
                $buyRequest = new Varien_Object($requestParams);

                $result = $wishlist->addNewItem($product, $buyRequest);
                if (is_string($result)) {
                    Mage::throwException($result);
                }
                $wishlist->save();

                Mage::dispatchEvent(
                    'wishlist_add_product',
                    array(
                        'wishlist' => $wishlist,
                        'product' => $product,
                        'item' => $result
                    )
                );

                Mage::helper('wishlist')->calculate();

                $message = $this->__('%1$s <span class="msg-text-regular">has been added to your wishlist.</span>',
                    $product->getName());
                $session->addSuccess($message);
            } catch (Mage_Core_Exception $e) {
                $session->addError($this->__('An error occurred while adding item to wishlist: %s', $e->getMessage()));
            }
            catch (Exception $e) {
                $session->addError($this->__('An error occurred while adding item to wishlist.'));
            }
            $response['messages'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();

            $response['minicart'] = true;
            $this->_sendAjaxResponse($response);
        }
    }

    private function _sendAjaxResponse($response)
    {
        $jsonData = Zend_Json::encode($response);
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($jsonData);
    }
}
