<?php
/**
 * User: mhidalgo
 * Date: 25/03/14
 * Time: 10:08
 */
require_once 'Mage/Checkout/controllers/CartController.php';
require_once 'Ayasoftware/SimpleProductPricing/controllers/Checkout/CartController.php';
class Tgc_Checkout_CartController extends Ayasoftware_SimpleProductPricing_Checkout_CartController
{

    /** @var int */
    protected $_digitalProductQty = 1;

    /**
     * Delete shoping cart item action
     */
    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $cart = $this->_getCart();
                if($associatedTranscriptProduct = $this->_helperTgcCheckout()->findAssociatedTranscriptProduct($id)) {
                    $cart->removeItem($associatedTranscriptProduct); //this removes the transcript product from the cart.
                }

                $cart->removeItem($id); //removes the product from the cart.
                $cart->save();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove the item.'));
                Mage::logException($e);
            }
        }
        if (!$this->getRequest()->isAjax()) {
            $this->_redirectReferer(Mage::getUrl('*/*'));
        } else {
            echo json_encode(
                array(
                "totals" => true,
                "cart" => Mage::helper('tgc_checkout')->getTableItemsCart($this->getLayout())
                )
            );
        }
    }

    public function addOptionAction()
    {
        $quote = $this->_getSession()->getQuote();
        $params = $this->getRequest()->getParams();

        $item = $quote->getItemById($params['item_id']);

        $data = array(
            'product' => $item->getProductId(),
            'options' => array(
                $params['option_id'] =>
                array(
                    $params['value_id']
                )
            ),
            'qty' => $item->getQty(),
            'form_key' => Mage::getSingleton('core/session')->getFormKey()
        );

        $children = $item->getChildren();
        if (isset($children[0]) && $children[0]->getProductId()) {
            $data['super_attribute'] = array(
                Mage::getResourceModel('catalog/product')->getAttribute('media_format')->getAttributeId()
                =>
                Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect('media_format')
                    ->addAttributeToFilter('entity_id', $children[0]->getProductId())
                    ->getFirstItem()
                    ->getMediaFormat()
            );
        }

        try {
            $quote->removeItem($item->getId());
            $quote->save();

            $this->getRequest()->setParams($data);
            $this->addAction();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * The reason why this function is created is because, if at some point, more params need to be passed to the addAction function.
     * It is much easier to customize data that is passed to addAction by creating a function below that can be customized.
     */
    public function addtranscriptitemAction()
    {
        $this->addAction();
    }

    public function removeOptionAction() {
        $quote   = $this->_getSession()->getQuote();
        $params = $this->getRequest()->getParams();

        $item = $quote->getItemById($params['item_id']);

        $data = array(
            'product' => $item->getProductId(),
            'qty' => $item->getQty(),
            'form_key' => Mage::getSingleton('core/session')->getFormKey()
        );

        $children = $item->getChildren();
        if (isset($children[0]) && $children[0]->getProductId()) {
            $data['super_attribute'] = array(
                Mage::getResourceModel('catalog/product')->getAttribute('media_format')->getAttributeId()
                =>
                Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect('media_format')
                    ->addAttributeToFilter('entity_id', $children[0]->getProductId())
                    ->getFirstItem()
                    ->getMediaFormat()
            );
        }

        try {
            $quote->removeItem($item->getId());

            //Removes a transcript item from the cart, if it exists.
            if($associatedTranscriptProductId = $this->_helperTgcCheckout()->findAssociatedTranscriptProduct($item->getId())) {
                $quote->removeItem($associatedTranscriptProductId); //this removes the transcript product from the cart.
            }

            $quote->save();

            $this->getRequest()->setParams($data);
            $this->addAction();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function refreshTotalsAction()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_goBack();
        }

        $this->_sendAjaxResponse(
            array(
                "totals" => Mage::helper('tgc_checkout')->getTableTotals($this->getLayout())
            )
        );

    }

    public function refreshReviewTotalsAction()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_goBack();
        }

        $this->_sendAjaxResponse(
            array(
                "reviewTotals" => Mage::helper('tgc_checkout')->getTableReviewTotals($this->getLayout())
            )
        );
    }

    public function addAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_goBack();
            return;
        }
        $cart = $this->_getCart();
        $params = $this->getRequest()->getParams();
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

            /**
             * Check product availability
             */
            if (!$product) {
                $this->_goBack();
                return;
            }

            $mediaAttributeIds = Mage::helper('tgc_catalog')->getDigitalMediaAttributeId();

            $mediaFormatAttributeId = $product->getResource()->getAttribute('media_format')->getId();
            $params['media-format-id'] = isset($params['super_attribute'][$mediaFormatAttributeId]) ? $params['super_attribute'][$mediaFormatAttributeId] : '';

            if (in_array($params['media-format-id'], $mediaAttributeIds) && array_key_exists('super_attribute', $params)) {
                $productCollection = Mage::getModel('catalog/product_type_configurable')
                    ->getUsedProductCollection($product);
                $attributesInfo = $params['super_attribute'];
                foreach ($attributesInfo as $attributeId => $attributeValue) {
                    $productCollection->addAttributeToFilter($attributeId, $attributeValue);
                }
                $productObject = $productCollection->getFirstItem();

                if ($this->_getSession()->getQuote()->hasProductId($productObject->getId())) {
                    if ($this->getRequest()->isAjax()) {
                        $message = $this->__('This item is already in your cart and could not be added again,<br />because a downloadable product can only be added to your cart once.');
                        $this->_getSession()->addNotice($message);
                    }
                    if (!$this->getRequest()->isAjax()) {
                        $this->_goBack();
                    } else {
                        $this->getLayout()->getMessagesBlock()->addMessages($this->_getSession()->getMessages(true));
                        $response = array();
                        $response['messages'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();

                        $response['minicart'] = true;
                        $this->_sendAjaxResponse($response);
                    }
                    return;
                }
            }

            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            /**
             * @todo remove wishlist observer processAddToCart
             */
            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError() && $this->getRequest()->isAjax()) {
                    $message = $this->__('%s <span class="msg-text-regular">was added to your Shopping Cart.</span>', Mage::helper('core')->escapeHtml($product->getName()));
                    $this->_getSession()->addSuccess($message);
                }
                if (!$this->getRequest()->isAjax()) {
                    $this->_goBack();
                } else {
                    $this->getLayout()->getMessagesBlock()->addMessages($this->_getSession()->getMessages(true));
                    $response = array();
                    $response['messages'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();

                    $response['minicart'] = true;
                    $this->_sendAjaxResponse($response);
                }
            }
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
                }
            }

            if ($this->getRequest()->isAjax()) {
                $this->getLayout()->getMessagesBlock()->addMessages($this->_getSession()->getMessages(true));
                $response = array();
                $response['messages'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
                $this->_sendAjaxResponse($response);
            } else {
                $url = $this->_getSession()->getRedirectUrl(true);
                if ($url) {
                    $this->getResponse()->setRedirect($url);
                } else {
                    $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
                }
            }
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            Mage::logException($e);
            if ($this->getRequest()->isAjax()) {
                $this->getLayout()->getMessagesBlock()->addMessages($this->_getSession()->getMessages(true));
                $response = array();
                $response['messages'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
                $this->_sendAjaxResponse($response);
            } else {
                $this->_goBack();
            }
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

    protected function _helperTgcCheckout()
    {
        return Mage::helper('tgc_checkout');
    }
}