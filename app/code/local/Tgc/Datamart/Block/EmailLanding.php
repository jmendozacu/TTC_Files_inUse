<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_EmailLanding extends Mage_Core_Block_Template
{
    public function _prepareLayout()
    {
        if ($head = $this->getLayout()->getBlock('head')) {
            $pageTitle = $this->getPageTitle();
            if ($pageTitle) {
                $head->setTitle($this->getPageTitle());
            }
            $pageMetaDescription = $this->getPageMetaDescription();
            if ($pageMetaDescription) {
                $head->setDescription($this->getPageMetaDescription());
            }
            $pageMetaKeywords = $this->getPageMetaKeywords();
            if ($pageMetaKeywords) {
                $head->setKeywords($this->getPageMetaKeywords());
            }
        }

        return parent::_prepareLayout();
    }

    /**
     * Set header and footer CMS block identifiers
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $bannerBlock = $this->getLayout()->getBlock('landing.page.banner');
        if ($this->getLandingPageDesign()
            && $this->getLandingPageDesign()->getLandingPageType() == Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE
            && $bannerBlock) {
            $bannerBlock->setDefaultMobileImage(
                Mage::helper('tgc_datamart/config')->getBuffetLandingBannerMobileImage()
            );
            $bannerBlock->setDefaultDesktopImage(
                Mage::helper('tgc_datamart/config')->getBuffetLandingBannerDesktopImage()
            );
        }

        if (!($bannerBlock
            && (($bannerBlock->getBanner() && $bannerBlock->getBanner()->getId())
                || $bannerBlock->getDefaultMobileImage() || $bannerBlock->getDefaultDesktopImage()))) {
            $headerCmsBlock = $this->getLayout()->getBlock('landing.page.header.cms.block');
            $headerCmsBlockIdentifier = $this->getHeaderCmsBlockIdentifier();
            if ($headerCmsBlock && $headerCmsBlockIdentifier) {
                $headerCmsBlock->setBlockId($headerCmsBlockIdentifier);
            }
        }

        $footerCmsBlock = $this->getLayout()->getBlock('landing.page.footer.cms.block');
        $footerCmsBlockIdentifier = $this->getFooterCmsBlockIdentifier();
        if ($footerCmsBlock && $footerCmsBlockIdentifier) {
            $footerCmsBlock->setBlockId($footerCmsBlockIdentifier);
        }

        return parent::_beforeToHtml();
    }

    /**
     * Landing page design getter
     *
     * @return Tgc_Datamart_Model_EmailLanding_Design
     */
    public function getLandingPageDesign()
    {
        if (!$this->hasData('landing_page_design')) {
            $this->setLandingPageDesign(Mage::registry('landing_page_design'));
        }

        return $this->getData('landing_page_design');
    }

    /**
     * Page title getter
     *
     * @return string
     */
    public function getPageTitle()
    {
        $pageTitle = '';
        if ($this->getLandingPageDesign()) {
            if ($this->getLandingPageDesign()->getTitle()) {
                $pageTitle = $this->getLandingPageDesign()->getTitle();
            } else {
                $pageTitle = Mage::helper('tgc_datamart/config')->getLandingTitle(
                    $this->getLandingPageDesign()->getLandingPageType()
                );
            }
        }

        return $pageTitle;
    }

    /**
     * Page meta description getter
     *
     * @return string
     */
    public function getPageMetaDescription()
    {
        $pageMetaDescription = '';
        if ($this->getLandingPageDesign()) {
            if ($this->getLandingPageDesign()->getDescription()) {
                $pageMetaDescription = $this->getLandingPageDesign()->getDescription();
            } else {
                $pageMetaDescription = Mage::helper('tgc_datamart/config')->getLandingMetaDescription(
                    $this->getLandingPageDesign()->getLandingPageType()
                );
            }
        }

        return $pageMetaDescription;
    }

    /**
     * Page meta keywords getter
     *
     * @return string
     */
    public function getPageMetaKeywords()
    {
        $pageMetaKeywords = '';
        if ($this->getLandingPageDesign()) {
            if ($this->getLandingPageDesign()->getKeywords()) {
                $pageMetaKeywords = $this->getLandingPageDesign()->getKeywords();
            } else {
                $pageMetaKeywords = Mage::helper('tgc_datamart/config')->getLandingMetaKeywords(
                    $this->getLandingPageDesign()->getLandingPageType()
                );
            }
        }

        return $pageMetaKeywords;
    }

    /**
     * Retrieve header CMS block identifier
     *
     * @return string
     */
    public function getHeaderCmsBlockIdentifier()
    {
        $headerCmsBlockIdentifier = '';
        if ($this->getLandingPageDesign()) {
            if ($this->getLandingPageDesign()->getHeaderId()) {
                $headerCmsBlockIdentifier = $this->getLandingPageDesign()->getHeaderId();
            } else {
                $headerCmsBlockIdentifier = Mage::helper('tgc_datamart/config')->getLandingHeaderBlockId(
                    $this->getLandingPageDesign()->getLandingPageType()
                );
            }
        }

        return $headerCmsBlockIdentifier;
    }

    /**
     * Retrieve footer CMS block identifier
     *
     * @return string
     */
    public function getFooterCmsBlockIdentifier()
    {
        $footerCmsBlockIdentifier = '';
        if ($this->getLandingPageDesign()) {
            if ($this->getLandingPageDesign()->getFooterId()) {
                $footerCmsBlockIdentifier = $this->getLandingPageDesign()->getFooterId();
            } else {
                $footerCmsBlockIdentifier = Mage::helper('tgc_datamart/config')->getLandingFooterBlockId(
                    $this->getLandingPageDesign()->getLandingPageType()
                );
            }
        }

        return $footerCmsBlockIdentifier;
    }
}
