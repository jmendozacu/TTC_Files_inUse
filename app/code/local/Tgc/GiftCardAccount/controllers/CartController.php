<?php
/**
 * User: mhidalgo
 * Date: 07/03/14
 * Time: 10:24
 */
require_once (Mage::getModuleDir('controllers', 'Enterprise_GiftCardAccount') . DS . 'CartController.php');
class Tgc_GiftCardAccount_CartController extends Enterprise_GiftCardAccount_CartController
{
    /**
     * Add Gift Card to current quote
     *
     */
    public function addAction()
    {
        $data = $this->getRequest()->getPost();
        if (isset($data['giftcard_code'])) {
            $code = $data['giftcard_code'];
            try {
                if (strlen($code) > Enterprise_GiftCardAccount_Helper_Data::GIFT_CARD_CODE_MAX_LENGTH) {
                    Mage::throwException(Mage::helper('enterprise_giftcardaccount')->__('Wrong gift card code.'));
                }
                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                    ->loadByCode($code)
                    ->addToCart();
                $message = $this->__('Gift Card "%s" was added.', Mage::helper('core')->escapeHtml($code));
                if (!$this->getRequest()->isAjax()) {
                    Mage::getSingleton('checkout/session')->addSuccess(
                        $message
                    );
                }
            } catch (Mage_Core_Exception $e) {
                Mage::dispatchEvent('enterprise_giftcardaccount_add', array('status' => 'fail', 'code' => $code));
                Mage::getSingleton('checkout/session')->addError(
                    $e->getMessage()
                );
            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException($e, $this->__('Cannot apply gift card.'));
            }
        }
        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('checkout/cart');
        } else {
            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode(array('totals' => true, 'message' => $message))
            );
        }
    }

    public function removeAction()
    {
        $result = false;
        $message = "";
        if ($code = $this->getRequest()->getParam('code')) {
            try {
                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                    ->loadByCode($code)
                    ->removeFromCart();
                $message = $this->__('Gift Card "%s" was removed.', Mage::helper('core')->escapeHtml($code));
                if (!$this->getRequest()->isAjax()) {
                    Mage::getSingleton('checkout/session')->addSuccess($message);
                }
            } catch (Mage_Core_Exception $e) {
                if (!$this->getRequest()->isAjax()) {
                    Mage::getSingleton('checkout/session')->addError(
                        $e->getMessage()
                    );
                } else {
                    $message = $e->getMessage();
                }
            } catch (Exception $e) {
                if (!$this->getRequest()->isAjax()) {
                    Mage::getSingleton('checkout/session')->addException($e, $this->__('Cannot remove gift card.'));
                } else {
                    $message = $this->__('Cannot remove gift card.');
                }
            }
            if (!$this->getRequest()->isAjax()) {
                $this->_redirect('checkout/cart');
            } else {
                $result = true;
            }
        } else {
            if (!$this->getRequest()->isAjax()) {
                $this->_forward('noRoute');
            } else {
                $result = false;
                $message = $this->__('Cannot remove gift card.');
            }
        }
        if ($this->getRequest()->isAjax()) {
            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode(array('result' => $result, 'message' => $message))
            );
        }
    }
}