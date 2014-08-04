<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $boutiqueId = $this->getRequest()->getParam('boutique_id', null);
        if (!$boutiqueId) {
            $boutiqueResource = Mage::getResourceModel('tgc_boutique/boutique');
            $boutiqueId = $boutiqueResource->getDefaultBoutiqueId();
        }
        $boutique = Mage::getModel('tgc_boutique/boutique')->load($boutiqueId, 'entity_id');
        $pageId = $this->getRequest()->getParam('page_id', null);
        if (!$pageId) {
            $boutiqueResource = Mage::getResourceModel('tgc_boutique/boutique');
            $pageId = $boutiqueResource->getDefaultPageForBoutique($boutiqueId);
        }
        $boutiquePage = Mage::getModel('tgc_boutique/boutiquePages')->load($pageId, 'entity_id');
        if (!$boutique->getId() || !$boutiquePage->getId()) {
            return $this->_pageNotExists();
        }

        Mage::register('current_boutique', $boutique, true);
        Mage::register('boutique_page', $boutiquePage, true);

        $this->loadLayout();

        $head = $this->getLayout()->getBlock('head');
        $head->setTitle($boutiquePage->getPageTitle());
        $head->setMetaKeyword($boutiquePage->getMetaKeywords());
        $head->setMetaDescription($boutiquePage->getMetaDescription());

        $processor = Mage::helper('cms')->getBlockTemplateProcessor();
        $header = $processor->filter(Mage::getModel('cms/block')->load($boutiquePage->getHeaderBlock())->getContent());
        $content = $processor->filter(Mage::getModel('cms/block')->load($boutiquePage->getContentBlock())->getContent());
        $footer = '';
        if ($footerBlock = $boutiquePage->getFooterBlock()) {
            $footer = $processor->filter(Mage::getModel('cms/block')->load($footerBlock)->getContent());
        }

        $hblock = $this->getLayout()->createBlock('core/text', 'boutique.header')->setText($header);
        $this->getLayout()->getBlock('content')->append($hblock);
        $cblock = $this->getLayout()->createBlock('core/text', 'boutique.content')->setText($content);
        $this->getLayout()->getBlock('content')->append($cblock);
        $fblock = $this->getLayout()->createBlock('core/text', 'boutique.footer')->setText($footer);
        $this->getLayout()->getBlock('content')->append($fblock);

        $this->renderLayout();
    }


    private function _pageNotExists()
    {
        Mage::app()->getFrontController()->getResponse()
            ->setRedirect(Mage::getUrl('noroute'))
            ->sendResponse();
        exit();
    }
}
