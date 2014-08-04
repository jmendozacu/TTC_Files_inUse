<?php
/**
 * Customer Active Session helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Model_ActiveSession extends Mage_Customer_Model_Session
{
    const ACTIVE_SESSION_SALT              = 'tgc';
    const COOKIE_NAME                      = 'tgc_active';
    const XML_PATH_ACTIVE_SESSION_LIFETIME = 'web/cookie/active_session_lifetime';
    const MAX_LOGIN_FAILURES               = 5;
    const ACCOUNT_LOCK_DURATION            = 1800;
    const UNKEPT_SESSION_LIFETIME          = 1800; // when they don't select 'keep logged in'
    const SESSION_TIMEOUT_MS               = 1800000; // 30 min
    const EXTENDED_SESSION_MS              = 7200000; // 2hhr
    const KEEP_LOGGED_COOKIE_NAME          = 'keep_logged';
    const EVENT_NAME_AUTH_AFTER            = 'customer_session_auth_after';

    private function _getActiveCookie()
    {
        /** @var $cookie Mage_Core_Model_Cookie */
        $cookie = Mage::getModel('core/cookie')->get(self::COOKIE_NAME);

        return $cookie;
    }

    public function getProtectedRoutes()
    {
        return array(
            'customer/account/*',
            'customer/account/index',
            'customer/account/edit',
            'customer/address/*',
            'checkout/cart/index',
            'spp/checkout_cart/index',
            'checkout/onepage/*',
            'sales/order/*',
            'sales/billing_agreement/*',
            'sales/recurring_profile/*',
            'review/customer/*',
            'newsletter/manage/*',
            'downloadable/customer/products',
            'storecredit/info/*',
            'digital-library/account/*',
            'digital-library/account/index',
            'tgc_digitallibrary_courses/*/*',
        );
    }

    private function _renewActiveCookie()
    {
        /** @var $cookie Mage_Core_Model_Cookie */
        $cookie    = Mage::getModel('core/cookie');
        $params    = session_get_cookie_params();
        $expire    = time() + $this->_getActiveSessionLength();
        $encrypted = $this->_getHelper()->encrypt($expire);

        $cookie->set(
            self::COOKIE_NAME,
            $encrypted,
            $params['lifetime'],
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );

        $_COOKIE[self::COOKIE_NAME] = $encrypted;
    }

    public function isCustomerActive()
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        $now = time();
        $expireTime = (int)$this->_getHelper()->decrypt($this->_getActiveCookie());

        return ($expireTime > $now);
    }

    private function _getActiveSessionLength()
    {
        return (int)Mage::getStoreConfig(self::XML_PATH_ACTIVE_SESSION_LIFETIME);
    }

    private function _getHelper()
    {
        return Mage::helper('tgc_customer/session');
    }

    public function startActiveSession()
    {
        $this->_renewActiveCookie();
    }

    public function endActiveSession()
    {
        /** @var $cookie Mage_Core_Model_Cookie */
        $cookie = Mage::getModel('core/cookie');
        $params = session_get_cookie_params();

        $cookie->delete(
            self::COOKIE_NAME,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );

        unset($_COOKIE[self::COOKIE_NAME]);
    }

    public function validateActiveSession($observer)
    {
        $request = $observer->getControllerAction()->getRequest();

        if ($request->getRequestedRouteName() == 'customer'
            && $request->getRequestedControllerName() == 'account'
            && $request->getRequestedActionName() == 'logout')
        {
            $this->endActiveSession();
            return;
        }

        $requestParts = array(
            $request->getRequestedRouteName(),
            $request->getRequestedControllerName(),
            $request->getRequestedActionName(),
        );

        $this->setLastRequest($requestParts);

        if (!$this->isLoggedIn()) {
            return;
        }

        if ($this->isCustomerActive()) {
            $this->_renewActiveCookie();
            return;
        }

        $this->endActiveSession();

        if ($this->_isRequestRouteProtected($requestParts)) {
            $beforeAuthUrl = trim($request->getOriginalPathInfo(), '/');
            $this->setBeforeAuthUrl(Mage::getUrl($beforeAuthUrl));
            $this->unsAfterAuthUrl();
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/verify'));
        }
    }

    public function needsLogoutRedirect()
    {
        $lastRequest = $this->getLastRequest();
        if (!is_array($lastRequest)) {
            return true;
        }
        return $this->_isRequestRouteProtected($lastRequest);
    }

    public function needsExtension()
    {
        $lastRequest = $this->getLastRequest();
        if (!is_array($lastRequest)) {
            return true;
        }
        return $this->_doesRequestRouteNeedExtension($lastRequest);
    }

    private function _doesRequestRouteNeedExtension($requestParts)
    {
        foreach ($this->getExtendedRoutes() as $route) {
            $routeParts = explode('/', $route);
            $diff = array_diff($routeParts, $requestParts);
            if (empty($diff)) {
                return true;
            }
            $wildcardMatch = true;
            foreach ($diff as $nonMatch) {
                if ($nonMatch != '*') {
                    $wildcardMatch = false;
                    break;
                }
            }
            if ($wildcardMatch) {
                return true;
            }
        }

        return false;
    }

    public function getExtendedRoutes()
    {
        return array(
            'digital-library/account/*',
            'digital-library/account/index',
            'digital-library/course/view',
            'tgc_digitallibrary_courses/*/*',
            'podcasts/index/index',
            'podcasts/podcast/view',
        );
    }

    private function _isRequestRouteProtected($requestParts)
    {
        foreach ($this->getProtectedRoutes() as $route) {
            $routeParts = explode('/', $route);
            $diff = array_diff($routeParts, $requestParts);

            if (empty($diff)) {
                return true;
            }

            $wildcardMatch = true;
            foreach ($diff as $nonMatch) {
                if ($nonMatch != '*') {
                    $wildcardMatch = false;
                    break;
                }
            }

            if ($wildcardMatch) {
                return true;
            }
        }

        return false;
    }

    public function login($username, $password)
    {
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId());

        if ($customer->authenticate($username, $password)) {
            $this->setCustomerAsLoggedIn($customer);
            $this->renewSession();
            $customer->unsNumFailures()->save();
            return true;
        }
        return false;
    }

    public function getActiveSessionLengthInMs()
    {
        $length = $this->_getActiveSessionLength();

        return $length * 1000;
    }

    /**
     * Reset core session hosts after reseting session ID
     *
     * @return Mage_Customer_Model_Session
     */
    public function renewSession()
    {
        parent::renewSession();
        $this->_updateSession();

        return $this;
    }

    /**
     * Set live cookie so ajax login will work
     * also, adjust session length for non keep me logged in customers
     */
    private function _updateSession()
    {
        $cookie      = $this->getCookie();
        $params      = session_get_cookie_params();
        $sessionId   = $this->getSessionId();
        $sessionName = Mage_Core_Controller_Front_Action::SESSION_NAMESPACE;

        if ($this->getKeepLogged() == false) {
            $params['lifetime'] = Tgc_Customer_Model_ActiveSession::UNKEPT_SESSION_LIFETIME;
        }

        $cookie->set($sessionName, $sessionId, $params['lifetime'], $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        $_COOKIE[$sessionName] = $sessionId;
    }

    public function getKeepLogged()
    {
        $cookie = $this->getCookie();
        $keepLogged = intval($cookie->get(self::KEEP_LOGGED_COOKIE_NAME));

        return $keepLogged;
    }

    public function authenticate(Mage_Core_Controller_Varien_Action $action, $loginUrl = null)
    {
        $result = parent::authenticate($action, $loginUrl);
        Mage::dispatchEvent(
            self::EVENT_NAME_AUTH_AFTER,
            array(
                'controller' => $action,
                'result'     => $result,
                'session'    => $this,
            )
        );

        return $result;
    }
}
