<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    const BOUTIQUE_MODULE_NAME        = 'boutique';
    const BOUTIQUE_CONTROLLER_NAME    = 'index';
    const BOUTIQUE_ACTION_NAME        = 'index';

    public function initControllerRouters(Varien_Event_Observer $observer)
    {
        $front = $observer->getEvent()->getFront();
        $front->addRouter('tgc_boutique', $this);
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        $pathInfo = trim($request->getPathInfo(), '/');
        $params = explode('/', $pathInfo);
        if (isset($params[0]) && $params[0] == self::BOUTIQUE_MODULE_NAME) {
            $boutiqueResource = Mage::getResourceModel('tgc_boutique/boutique');
            $boutiqueKey = isset($params[1]) ? $params[1] : null;
            $boutiqueId = $boutiqueResource->getBoutiqueIdByKey($boutiqueKey);
            if (!$boutiqueId) {
                $boutiqueId = $boutiqueResource->getDefaultBoutiqueId();
            }
            if (!$boutiqueId) {
                return false; //404 experience
            }
            $request->setParam('boutique_id', $boutiqueId);

            $pageResource = Mage::getResourceModel('tgc_boutique/boutiquePages');
            $pageKey = isset($params[2]) ? $params[2] : null;
            $pageId = $pageResource->getPageIdByKey($pageKey);
            if (!$pageId) {
                $pageId = $boutiqueResource->getDefaultPageForBoutique($boutiqueId);
            }
            if (!$pageId) {
                return false; //404 experience
            }
            $request->setParam('page_id', $pageId);

            $request->setModuleName(self::BOUTIQUE_MODULE_NAME)
                ->setControllerName(self::BOUTIQUE_CONTROLLER_NAME)
                ->setActionName(self::BOUTIQUE_ACTION_NAME);

            $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $pathInfo
            );
            return true;
        }

        return false;
    }
}
