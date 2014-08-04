<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    private $_routes = array(
        'account/info'         => array('customer',           'account',         'index'),
        'account/login'        => array('customer',           'account',         'login'),
        'account/logout'       => array('customer',           'account',         'logout'),
        'account/orders'       => array('sales',              'order',           'history'),
        'account/wishlist'     => array('wishlist',           'index',           'index'),
        'account/newsletter'   => array('newsletter',         'manage',          'index'),
        'account/creditcards'  => array('enterprise_pbridge', 'payment_profile', 'index'),
        'search/result'        => array('catalogsearch',      'result',          'index'),
        'account/qa'           => array('qa',                 'customer',        'index'),
        'account/review'       => array('review',             'customer',        'index')
    );

    public function register(Varien_Event_Observer $observer)
    {
        $observer->getEvent()->getFront()->addRouter('tgc_customer', $this);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if ($request->getModuleName() && $request->getControllerName() && $request->getActionName()) {
            return false;
        }

        $route = $this->_getRouteByRequest($request);
        if ($route) {
            list ($module, $controller, $action) = $route;
            $request->setRouteName('tgc_customer')
                ->setModuleName($module)
                ->setControllerName($controller)
                ->setActionName($action);
            return true;
        }

        return false;
    }

    private function _getRouteByRequest(Zend_Controller_Request_Http $request)
    {
        $path = trim($request->getPathInfo(), '/');

        return isset($this->_routes[$path]) ? $this->_routes[$path] : false;
    }
}