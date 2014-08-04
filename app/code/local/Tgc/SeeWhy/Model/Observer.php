<?php
/**
 * SeeWhy
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     SeeWhy
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SeeWhy_Model_Observer
{
    private $_html = '';
    private $_request;

    const JS_VAR_GUEST        = 'Guest';
    const JS_VAR_MAILSHOT     = 'mailshot';
    const JS_VAR_INTENT       = 'intent';
    const JS_CHECKOUT_LOGIN   = 'Checkout Login';
    const JS_CHECKOUT         = 'Checkout';
    const JS_ACCOUNT_SIGN_IN  = 'Account sign-in';
    const JS_ACCOUNT_CREATE   = 'Account creation';
    const CHECKOUT_MODULE     = 'checkout';
    const CHECKOUT_CONTROLLER = 'onepage';
    const CHECKOUT_ACTION     = 'index';
    const SUCCESS_ACTION      = 'success';

    public function addSeeWhyJs($observer)
    {
        $response = $observer->getResponse();
        if ($this->_isAjax() || Mage::helper('tgc_checkout')->isPaymentBridgeContext()) {
            return;
        }

        $this->_addCyImage();
        $this->_addLandingPageJs();
        $this->_addAddToCartJs();
        $this->_addCheckoutEmailCaptureJs();
        $this->_addEmailCaptureJs();
        $this->_addAccountCreateJs();
        $this->_addCheckoutNameCaptureJs();
        $this->_addCheckoutSuccessActionJs();

        $response->setBody(
            str_replace('</body>', $this->_html . '</body>', $response->getBody(false))
        );
    }

    protected function _isAjax()
    {
        if ($this->_isXmlHttpRequest()) {
            return true;
        }

        return false;
    }

    public function _isXmlHttpRequest()
    {
        return ($this->_getRequest()->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
    }

    private function _addCheckoutSuccessActionJs()
    {
        if (!$this->_isSuccess()) {
            return;
        }

        $session        = Mage::getSingleton('checkout/session');
        $orderId        = $session->getLastOrderId();
        $order          = Mage::getModel('sales/order')->load($orderId);
        if (!$order->getId()) {
            return;
        }

        $varOrderNumber = $order->getIncrementId();
        $varTotal       = $order->getSubtotal();
        $varEmail       = $order->getCustomerEmail();

        $js = <<<CHECKOUT_SUCCESS_ACTION_JS
<script type="text/javascript">
    jQuery(document).ready(function() {
        cy.FunnelLevel  = "7";
        cy.OrderNumber  = "{$varOrderNumber}";
        cy.Value        = {$varTotal};
        cy.UserId       = "{$varEmail}";

        cy_getImageSrc();
    });
</script>

CHECKOUT_SUCCESS_ACTION_JS;

        $this->_html .= $js;
    }

    private function _addCheckoutNameCaptureJs()
    {
        if (!$this->_isCheckout()) {
            return;
        }

        $varCheckout = self::JS_CHECKOUT;

        $js = <<<CHECKOUT_NAME_CAPTURE_JS
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(document).on('change', '[name="billing[firstname]"]', function() {
            checkoutNameChange(jQuery(this).val());
        });
    });

    function checkoutNameChange(name) {
        cy.FunnelLevel = "5";
        cy.Custom2     = "{$varCheckout}";
        cy.Custom1     = name;

        cy_getImageSrc();

        return true;
    }
</script>

CHECKOUT_NAME_CAPTURE_JS;

        $this->_html .= $js;
    }

    private function _addAccountCreateJs()
    {
        $varAccountCreate = self::JS_ACCOUNT_CREATE;

        $js = <<<ACCOUNT_CREATE_JS
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(document).on('change', 'input#email_address', function() {
            newUserInputChange(jQuery(this).val());
        });
        jQuery(document).on('change', 'input#mobile-create-user-input', function() {
            newUserInputChange(jQuery(this).val());
        });
    });

    function newUserInputChange(email) {
        cy.FunnelLevel = "2";
        cy.Custom1     = jQuery('#firstname').val();
        cy.Custom2     = "{$varAccountCreate}";
        cy.UserId      = email;

        cy_getImageSrc();

        return true;
    }
</script>

ACCOUNT_CREATE_JS;

        $this->_html .= $js;
    }

    private function _addEmailCaptureJs()
    {
        $varAccountSignIn = self::JS_ACCOUNT_SIGN_IN;

        $js = <<<EMAIL_CAPTURE_JS
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(document).on('change', 'input#email', function() {
            emailInputChange(jQuery(this).val());
        });
        jQuery(document).on('change', 'input#mobile-user-input', function() {
            emailInputChange(jQuery(this).val());
        });
        jQuery(document).on('change', '[name="freelectures[email_address]"]', function() {
            checkoutEmailInputChange(jQuery(this).val());
        });
    });

    function emailInputChange(email) {
        cy.FunnelLevel = "2";
        cy.Custom2     = "{$varAccountSignIn}";
        cy.UserId      = email;

        cy_getImageSrc();

        return true;
    }
</script>

EMAIL_CAPTURE_JS;

        $this->_html .= $js;
    }

    private function _addCheckoutEmailCaptureJs()
    {
        if (!$this->_isCheckout()) {
            return;
        }

        $varCheckoutLogin = self::JS_CHECKOUT_LOGIN;

        $js = <<<CHECKOUT_EMAIL_CAPTURE_JS
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(document).on('change', '[name="registercheckout[email]"]', function() {
            checkoutEmailInputChange(jQuery(this).val());
        });
        jQuery(document).on('change', '#login-email', function() {
            checkoutEmailInputChange(jQuery(this).val());
        });
    });

    function checkoutEmailInputChange(email) {
        cy.FunnelLevel = "4";
        cy.Custom2     = "{$varCheckoutLogin}";
        cy.UserId      = email;

        cy_getImageSrc();

        return true;
    }
</script>

CHECKOUT_EMAIL_CAPTURE_JS;

        $this->_html .= $js;
    }

    private function _addAddToCartJs()
    {
        $product = Mage::registry('current_product');
        if (!$product || !$product->getId()) {
            return;
        }

        $productId = $product->getId();
        $url       = Mage::getUrl('cy/fetch/addToCartData');

        $js = <<<ADD_TO_CART_JS
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery.ajax({
            type: 'POST',
            url: '{$url}',
            data: {
                productId: '{$productId}'
            },
            success: function(data, textStatus, jqXHR) {
                if (typeof productAddToCartForm != 'undefined') {
                    prepareSeeWhyAddToCartForm(data);
                }
            }
        });

        function prepareSeeWhyAddToCartForm(data) {
            var ajaxAddToCart = productAddToCartForm.submit;
            productAddToCartForm.submit = function(button, url) {
                var itemPrice   = jQuery('div.format-block.active').find('span.format-price').text().replace(data['currencySymbol'], '');

                cy.FunnelLevel  = "3";
                cy.Custom1      = data['name'];
                cy.Custom2      = data['intent'];
                cy.Value        = parseFloat(data['cartTotal']) + parseFloat(itemPrice);
                cy.ReturnToLink = data['cartUrl'];
                cy.UserId       = data['email'];

                cyNewBasketLine();
                cyAddBasketLineDetail("ItemName",             data['productName']);
                cyAddBasketLineDetail("ItemFormat",           jQuery('div.format-block.active').find('span.format-label').text());
                cyAddBasketLineDetail("ItemImageURL",         data['productImage']);
                cyAddBasketLineDetail("ItemProfessor",        data['professorName']);
                cyAddBasketLineDetail("ItemProfImageURL",     data['professorImage']);
                cyAddBasketLineDetail("ItemPrice",            itemPrice);
                cyAddBasketLineDetail("ItemPageURL",          data['pageUrl']);
                cyAddBasketLineDetail("ItemReviewScoreImage", data['productReviewImage']);
                cyAddBasketLineDetail("ItemReviewScore",      data['productReviewScore']);

                cy_getImageSrc();

                ajaxAddToCart(button, url);
            }
        }
    });
</script>

ADD_TO_CART_JS;

        if (!empty($js)) {
            $this->_html .= $js;
        }
    }

    private function _addLandingPageJs()
    {
        $varGuest    = self::JS_VAR_GUEST;
        $varMailshot = self::JS_VAR_MAILSHOT;

        $js = <<<LANDING_PAGE_JS
<script type="text/javascript">
    function cyCheckQueryStringForParam(queryName) {
        var queryString = window.location.search.substring(1);
        var queryStringSplit = queryString.split("&");
        for (var i = 0; i < queryStringSplit.length; i++) {
            var queryResult = queryStringSplit[i].split("=");
            if (queryResult[0] == queryName) {
                return queryResult[1];
            }
        }

        return false;
    }

    if (cyCheckQueryStringForParam("cyEmail")) {
        cy.Custom1 = "{$varGuest}";
        cy.Custom2 = "{$varMailshot}";
        cy.UserId = unescape(cyCheckQueryStringForParam("cyEmail"));
        cy.FunnelLevel = "0";
        cy_getImageSrc();
    }
</script>

LANDING_PAGE_JS;

        $this->_html .= $js;
    }

    private function _addCyImage()
    {
        $image = <<<CY_IMAGE
<img id="cy_image" width="1" height="1" alt="SeeWhy" style="display:none;" />

CY_IMAGE;

        $this->_html .= $image;
    }

    private function _isCheckout()
    {
        $request = $this->_getRequest();

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        return (bool)(
            self::CHECKOUT_MODULE == $module &&
            self::CHECKOUT_CONTROLLER == $controller &&
            self::CHECKOUT_ACTION == $action
        );
    }

    private function _isSuccess()
    {
        $request = $this->_getRequest();

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        return (bool)(
            self::CHECKOUT_MODULE == $module &&
                self::CHECKOUT_CONTROLLER == $controller &&
                self::SUCCESS_ACTION == $action
        );
    }

    private function _getRequest()
    {
        if (isset($this->_request)) {
            return $this->_request;
        }

        $this->_request = Mage::app()->getFrontController()->getRequest();

        return $this->_request;
    }
}
