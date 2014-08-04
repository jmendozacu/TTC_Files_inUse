<?php
/**     ___
 *     /___\_/
 *     |\_/|<\
 *     (`o`) `   __(\_            |\_
 *     \ ~ /_.-`` _|__)  ( ( ( ( /()/
 *    _/`-`  _.-``               `\|
 * .-`      (    .-.
 *(   .-     \  /   `-._
 * \  (\_    /\/        `-.__-()
 *  `-|__)__/ /  /``-.   /_____8
 *        \__/  /     `-`      `
 *       />|   /(
 *      /| J   L
 *      `` |   |
 *         L___J
 *          ( |
 *         .oO()
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     CookieNinja
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_CookieNinja_Model_Ninja
{
    //cookie names
    const COOKIE_STORE           = 'store';
    const COOKIE_IS_PROSPECT     = 'IsProspect';
    const COOKIE_CATALOG_CODE    = 'Catalogcode';
    const COOKIE_AD_CODE         = 'strAdcode';
    const COOKIE_COUPON_ADDED    = 'couponAdded';
    const COOKIE_EDIT_CART_USER  = 'EditCartUser';
    const COOKIE_RECENTLY_VIEW   = 'RecentlyView';
    const COOKIE_AFFILIATE_ID    = 'affiliateid';
    const COOKIE_LOGGED_IN       = 'LoggedIn';
    const COOKIE_USER_ID         = 'UserId';
    const COOKIE_NEW_USER        = 'NewUser';

    //cookie expire times
    const STORE_EXPIRES          = 2592000; // 30 days
    const IS_PROSPECT_EXPIRES    = 2592000; // 30 days
    const CATALOG_CODE_EXPIRES   = 2592000; // 30 days
    const AD_CODE_EXPIRES        = 2592000; // 30 days
    const COUPON_ADDED_EXPIRES   = 86400;   // 24 hours
    const EDIT_CART_USER_EXPIRES = 2592000; // 30 days
    const RECENTLY_VIEW_EXPIRES  = 86400;   // 24 hours
    const AFFILIATE_ID_EXPIRES   = 2592000; // 30 days
    const SHORT_TERM_EXPIRY      = 86400;   // 24 hours
    const FOUR_DAYS              = 259200;  // 72 hours

    const CUSTOMER_TYPE_USER     = 0;
    const CUSTOMER_TYPE_GUEST    = 1;
    const CUSTOMER_TYPE_PROSPECT = 1;

    const REGISTRY_PREFIX        = 'cookie_action_';
    const COUPON_CODE_PARAM      = 'coupon_code';
    const EDIT_CART_USER_VALUE   = 'true';
    const CHECKOUT_MODULE        = 'checkout';
    const SPP_MODULE             = 'spp';
    const CHECKOUT_CONTROLLER    = 'onepage';
    const CART_MODULE            = 'checkout';
    const CART_CONTROLLER        = 'cart';
    const CART_ACTION            = 'index';
    const CHECKOUT_ACTION        = 'index';
    const LAST_IN_CHECKOUT_VAR   = 'last_in_checkout';
    const AD_CODE_WATCH_PARAM    = 'ai';
    const AFFILIATE_WATCH_PARAM  = 'affiliateid';
    const QUERY_PARAMS_ACTION    = 'queryParamsAction';

    const DEFAULT_US_ADCODE      = '16281';
    const DEFAULT_UK_ADCODE      = '37077';
    const DEFAULT_AU_ADCODE      = '52786';
    const DEFAULT_US_AUTH_ADCODE = '16281';
    const DEFAULT_UK_AUTH_ADCODE = '37077';
    const DEFAULT_AU_AUTH_ADCODE = '52786';

    private $_request;

    private function _getRequest()
    {
        if (isset($this->_request)) {
            return $this->_request;
        }

        $this->_request = Mage::app()->getFrontController()->getRequest();

        return $this->_request;
    }

    private function _getCustomer()
    {
        return $this->_getSession()->getCustomer();
    }

    private function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    private function _getCookie()
    {
        $cookie = Mage::getModel('core/cookie');

        return $cookie;
    }

    private function _isHandled($name)
    {
        return (bool)Mage::registry(self::REGISTRY_PREFIX . $name);
    }

    private function _markHandled($name)
    {
        Mage::register(self::REGISTRY_PREFIX . $name, true, true);
    }

    private function _cookieExists($name)
    {
        if ($this->_getCookie()->get($name) || $this->_getCookie()->get($name) === self::CUSTOMER_TYPE_USER) {
            return true;
        }

        return false;
    }

    private function _setCookie($name = null, $value = '', $period = null)
    {
        $_COOKIE[$name] = $value;
        $this->_getCookie()->set($name, $value, $period, null, null, null, false);
    }

    public function getCookie($name)
    {
        return $this->_getCookie()->get($name);
    }

    public function deleteCookie($name)
    {
        unset($_COOKIE[$name]);
        $this->_getCookie()->delete($name);
    }

    private function _renewCookie($name, $period)
    {
        $this->_getCookie()->renew($name, $period);
    }

    public function setStoreCookie(Varien_Event_Observer $observer)
    {
        $eventName = $observer->getEvent()->getName();
        $cookieName = self::COOKIE_STORE;
        if (!in_array($eventName, array('customer_login', 'customer_logout'))
            && ($this->_cookieExists($cookieName) || $this->_isHandled($cookieName))) {
            return;
        }

        if (!$this->_cookieExists($cookieName)) {
            $this->_setNewUserCookie();
        }

        if ($eventName == 'customer_logout') {
            $customer = new Varien_Object();
        } else {
            $customer = $this->_getCustomer();
        }

        if ($customer->getWebUserId()) {
            $webUserId = $customer->getWebUserId();
        } else {
            $userData = array(
                $customer->getEmail(),
                microtime(true),
                mt_rand(),
            );
            $webUserId = Mage::helper('tgc_customer')->generateWebUserId($userData);
        }

        $streamEligible = 'False';
        if (!$customer->getIsProspect() && $this->_getSession()->isLoggedIn()
            && $eventName != 'customer_logout') {
            $streamEligible = 'True';
        }

        $data = array(
            'userID'         => $webUserId,
            'StreamEligible' => $streamEligible,
            'email'          => $customer->getEmail() ? $customer->getEmail() : '',
            'firstname'      => $customer->getFirstname() ? $customer->getFirstname() : '',
            'lastname'       => $customer->getLastname() ? $customer->getLastname() : '',
        );

        $cookieData = http_build_query($data);

        $this->_setCookie($cookieName, $cookieData, self::STORE_EXPIRES);
        $this->_markHandled($cookieName);
    }

    public function renewStoreCookie(Varien_Event_Observer $observer)
    {
        $this->_renewCookie(self::COOKIE_STORE, self::STORE_EXPIRES);
    }

    public function setIsProspectCookie(Varien_Event_Observer $observer)
    {
        $cookieName = self::COOKIE_IS_PROSPECT;
        if ($this->_isHandled($cookieName) || !$this->_getSession()->isLoggedIn()) {
            return;
        }

        if ($this->_getCustomer()->getIsProspect()) {
            $customerType = self::CUSTOMER_TYPE_PROSPECT;
        } else {
            $customerType = self::CUSTOMER_TYPE_USER;
        }

        $this->_setCookie($cookieName, $customerType, self::IS_PROSPECT_EXPIRES);
        $this->_markHandled($cookieName);
    }

    public function updateIsProspectCookie(Varien_Event_Observer $observer)
    {
        $this->_setCookie(self::COOKIE_IS_PROSPECT, self::CUSTOMER_TYPE_USER, self::IS_PROSPECT_EXPIRES);
    }

    public function setCatalogCodeCookie(Varien_Event_Observer $observer)
    {
        $adCode = $observer->getEvent()->getAdcode();
        $customerGroupId = $adCode->getCustomerGroupId();
        $catalogCode = Mage::getModel('customer/group')->load($customerGroupId)->getCatalogCode();

        $this->_setCookie(self::COOKIE_CATALOG_CODE, $catalogCode, self::CATALOG_CODE_EXPIRES);
    }

    public function checkAdCodeApplied(Varien_Event_Observer $observer)
    {
        $cookieName = self::COOKIE_AD_CODE;
        if ($this->_isHandled($cookieName)) {
            return;
        }

        if (!$this->_validateAdCode()) {
            $this->_ruinAdCode();
        }

        if (!$this->_cookieExists($cookieName) && !$this->_getSession()->getAdCode()) {
            $this->_setCookie(self::COOKIE_AD_CODE, Mage::helper('ninja/ninja')->getDefaultAdcode(), self::AD_CODE_EXPIRES);
        } else if (!$this->_cookieExists($cookieName)) {
            $this->_setCookie(self::COOKIE_AD_CODE, $this->_getSession()->getAdCode(), self::AD_CODE_EXPIRES);
        }

        Mage::getModel('tgc_price/adCode_processor')->changePrices($this->_getCookie()->get($cookieName));
        $this->_markHandled($cookieName);
    }

    private function _ruinAdCode()
    {
        $this->_getSession()->unsAdCode();
        $this->_getSession()->setCustomerGroupId(0);
        $this->_getSession()->unsAdCodeCustomerGroupId();
        $this->_getSession()->getCustomer()->setCustomerGroupId(0);
        $this->deleteCookie(self::COOKIE_AD_CODE);
        Mage::helper('checkout/cart')->getQuote()->setCustomerGroupId(0)->save();
    }

    private function _validateAdCode()
    {
        $cookieName = self::COOKIE_AD_CODE;
        $adCode = $this->_getSession()->getAdCode();
        if (!$adCode) {
            $adCode = $this->_getCookie()->get($cookieName);
        }

        if (!$adCode) {
            return true;
        }

        $adCode = Mage::getModel('tgc_price/adCode')->load($adCode);
        $groupId = $adCode->getCustomerGroupId();
        $groups = Mage::getModel('customer/group')
            ->getCollection()
            ->addFieldToFilter('customer_group_id', array('eq' => $groupId))
            ->addFieldToFilter('start_date',
            array(
                array('to' => Mage::getModel('core/date')->gmtDate()),
                array('start_date', 'null' => ''))
        )
            ->addFieldToFilter('stop_date',
            array(
                array('gteq' => Mage::getModel('core/date')->gmtDate()),
                array('stop_date', 'null' => ''))
        )
            ->addFieldtoFilter('website_id', array('in' => array(0, Mage::app()->getWebsite()->getId())));

        return count($groups) > 0 ? true : false;
    }

    public function setAdCodeCookie(Varien_Event_Observer $observer)
    {
        $adCode = $observer->getEvent()->getAdcode();

        $this->_setCookie(self::COOKIE_AD_CODE, $adCode->getCode(), self::AD_CODE_EXPIRES);
    }

    public function setCouponAddedCookie(Varien_Event_Observer $observer)
    {
        $cookieName = self::COOKIE_COUPON_ADDED;
        if ($this->_isHandled($cookieName)) {
            return;
        }

        $request = $this->_getRequest();
        if ($param = $request->getParam(self::COUPON_CODE_PARAM)) {
            $this->_setCookie($cookieName, $param, self::COUPON_ADDED_EXPIRES);
        }
        $this->_markHandled($cookieName);
    }

    public function setEditCartUserCookie(Varien_Event_Observer $observer)
    {
        $cookieName = self::COOKIE_EDIT_CART_USER;
        if ($this->_isHandled($cookieName)) {
            return;
        }

        $request    = $this->_getRequest();
        $controller = $request->getControllerName();
        $module     = $request->getModuleName();
        $action     = $request->getActionName();
        if ($controller == self::CHECKOUT_CONTROLLER && $controller == self::CHECKOUT_CONTROLLER) {
            $isCheckout = true;
            $this->_getSession()->setData(self::LAST_IN_CHECKOUT_VAR, true);
        } else {
            $isCheckout = false;
        }

        if (!$isCheckout && $this->_getSession()->getData(self::LAST_IN_CHECKOUT_VAR) && $module == self::CHECKOUT_MODULE && $controller == self::CART_CONTROLLER && $action == self::CART_ACTION) {
            $this->_setCookie($cookieName, self::EDIT_CART_USER_VALUE, self::EDIT_CART_USER_EXPIRES);
            $this->_getSession()->unsData(self::LAST_IN_CHECKOUT_VAR);
        } else if (!$isCheckout) {
            $this->_getSession()->unsData(self::LAST_IN_CHECKOUT_VAR);
        }

        $this->_markHandled($cookieName);
    }

    public function setRecentlyViewCookie(Varien_Event_Observer $observer)
    {
        $product = Mage::registry('current_product');
        if($product && $product->getId() && $product->getCourseId()) {
            $this->_setCookie(self::COOKIE_RECENTLY_VIEW, $product->getCourseId(), self::RECENTLY_VIEW_EXPIRES);
        }
    }

    public function setCookiesBeforeRedirect(Varien_Event_Observer $observer)
    {
        $request = $this->_getRequest();
        /* @var $urlRewrite Enterprise_UrlRewrite_Model_Url_Rewrite */
        $urlRewrite = $observer->getEvent()->getUrlRewrite();
        if ($urlRewrite->getId()) {

            $processor = Mage::getSingleton('ninja/urlRewriteProcessor_factory')
                ->getUrlRewriteProcessor($request, $urlRewrite);
            if ($processor) {
                $processor->process($request, $urlRewrite);
            }
        }
    }

    public function handleQuerystringParams(Varien_Event_Observer $observer)
    {
        if ($this->_isHandled(self::QUERY_PARAMS_ACTION)) {
            return;
        }

        $request = $this->_getRequest();
        if ($param = $request->getParam(self::AD_CODE_WATCH_PARAM)) {
            if (Mage::helper('ninja/ninja')->validateAdcode($param)) {
                Mage::getModel('tgc_price/adCode_processor')->changePrices($param);
            }
        }
        if ($param = $request->getParam(self::AFFILIATE_WATCH_PARAM)) {
            $this->_setCookie(self::COOKIE_AFFILIATE_ID, $param, self::AFFILIATE_ID_EXPIRES);
        }

        $this->_markHandled(self::QUERY_PARAMS_ACTION);
    }

    public function setCustomerCookie(Varien_Event_Observer $observer)
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_setCookie(self::COOKIE_LOGGED_IN, '1');
        } else {
            $this->_setCookie(self::COOKIE_LOGGED_IN, '0');
        }

        $storeData = $this->getCookie(self::COOKIE_STORE);
        $data = array();
        parse_str($storeData, $data);
        if (isset($data['userID'])) {
            $this->_setCookie(self::COOKIE_USER_ID, $data['userID']);
        }
    }

    protected function _setNewUserCookie()
    {
        $this->_setCookie(self::COOKIE_NEW_USER, '1', self::STORE_EXPIRES);
    }

    public function isCustomerNew()
    {
        return $this->getCookie(self::COOKIE_NEW_USER) ? true : false;
    }

    public function unsetNewUserCookie()
    {
        $this->deleteCookie(self::COOKIE_NEW_USER);
    }

    public function unsetAdcode(Varien_Event_Observer $observer)
    {
        $this->_ruinAdCode();
        $this->setStoreCookie($observer);
    }
}
