<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function match(Zend_Controller_Request_Http $request)
    {
        if ($request->getModuleName() && $request->getControllerName() && $request->getActionName()) {
            return false;
        }

        $pathInfo = trim($request->getPathInfo(), '/');
        if (!preg_match('#^professors/([^/]+)$#i', $pathInfo, $m)) {
            return false;
        }

        $id = Mage::getModel('profs/professor')->load($m[1])->getId();

        $request->setRouteName('profs')
            ->setModuleName('profs')
            ->setControllerName('professor')
            ->setActionName('view')
            ->setParam('id', $id)
            ->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $pathInfo
            );
        return true;
    }

    public function register(Varien_Event_Observer $observer)
    {
        $observer->getEvent()->getFront()->addRouter('professors', $this);
    }
}