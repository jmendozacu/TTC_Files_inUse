<?php
require_once (Mage::getModuleDir('controllers', 'Mage_CatalogSearch') . DS . 'AjaxController.php');
/**
 * Solr search
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Solr
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_AjaxController extends Mage_CatalogSearch_AjaxController
{
    public function suggestAction()
    {
        if (!$this->getRequest()->getParam('q', false)) {
            $this->getResponse()->setRedirect(Mage::getSingleton('core/url')->getBaseUrl());
        }

        $this->getResponse()->setBody($this->getLayout()->createBlock('tgc_solr/autocomplete')->toHtml());
    }
}
