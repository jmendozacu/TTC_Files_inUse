<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     * Config paths
     */
    const XML_PATH_BUFFET_LANDING_TITLE                = 'datamart/buffet_landing/title';
    const XML_PATH_BUFFET_LANDING_DESCRIPTION          = 'datamart/buffet_landing/description';
    const XML_PATH_BUFFET_LANDING_KEYWORDS             = 'datamart/buffet_landing/keywords';
    const XML_PATH_BUFFET_LANDING_HEADER_BLOCK_ID      = 'datamart/buffet_landing/header';
    const XML_PATH_BUFFET_LANDING_FOOTER_BLOCK_ID      = 'datamart/buffet_landing/footer';
    const XML_PATH_BUFFET_LANDING_BANNER_MOBILE_IMAGE  = 'datamart/buffet_landing/banner_mobile_image';
    const XML_PATH_BUFFET_LANDING_BANNER_DESKTOP_IMAGE = 'datamart/buffet_landing/banner_desktop_image';

    const XML_PATH_EMAIL_LANDING_TITLE           = 'datamart/email_landing/title';
    const XML_PATH_EMAIL_LANDING_DESCRIPTION     = 'datamart/email_landing/description';
    const XML_PATH_EMAIL_LANDING_KEYWORDS        = 'datamart/email_landing/keywords';
    const XML_PATH_EMAIL_LANDING_HEADER_BLOCK_ID = 'datamart/email_landing/header';
    const XML_PATH_EMAIL_LANDING_FOOTER_BLOCK_ID = 'datamart/email_landing/footer';

    const XML_PATH_RADIO_LANDING_TITLE              = 'datamart/radio_landing/title';
    const XML_PATH_RADIO_LANDING_DESCRIPTION        = 'datamart/radio_landing/description';
    const XML_PATH_RADIO_LANDING_KEYWORDS           = 'datamart/radio_landing/keywords';
    const XML_PATH_RADIO_LANDING_HEADER_BLOCK_ID    = 'datamart/radio_landing/header';
    const XML_PATH_RADIO_LANDING_FOOTER_BLOCK_ID    = 'datamart/radio_landing/footer';
    const XML_PATH_RADIO_LANDING_DEFAULT_MEDIA_CODE = 'datamart/radio_landing/default_media_code';

    /**
     * Retrieve landing page default title from config
     *
     * @param integer $pageType
     * @return string
     */
    public function getLandingTitle($pageType)
    {
        switch ($pageType) {
            case Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE:
                return $this->getBuffetLandingTitle();
            case Tgc_Datamart_Model_Source_LandingPage_Type::EMAIL_VALUE:
                return $this->getEmailLandingTitle();
            case Tgc_Datamart_Model_Source_LandingPage_Type::RADIO_VALUE:
                return $this->getRadioLandingTitle();
            default:
                return '';
        }
    }

    /**
     * Retrieve landing page default meta description from config
     *
     * @param integer $pageType
     * @return string
     */
    public function getLandingMetaDescription($pageType)
    {
        switch ($pageType) {
            case Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE:
                return $this->getBuffetLandingMetaDescription();
            case Tgc_Datamart_Model_Source_LandingPage_Type::EMAIL_VALUE:
                return $this->getEmailLandingMetaDescription();
            case Tgc_Datamart_Model_Source_LandingPage_Type::RADIO_VALUE:
                return $this->getRadioLandingMetaDescription();
            default:
                return '';
        }
    }

    /**
     * Retrieve landing page default meta keywords from config
     *
     * @param integer $pageType
     * @return string
     */
    public function getLandingMetaKeywords($pageType)
    {
        switch ($pageType) {
            case Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE:
                return $this->getBuffetLandingMetaKeywords();
            case Tgc_Datamart_Model_Source_LandingPage_Type::EMAIL_VALUE:
                return $this->getEmailLandingMetaKeywords();
            case Tgc_Datamart_Model_Source_LandingPage_Type::RADIO_VALUE:
                return $this->getRadioLandingMetaKeywords();
            default:
                return '';
        }
    }

    /**
     * Retrieve landing page default header block id from config
     *
     * @param integer $pageType
     * @return string
     */
    public function getLandingHeaderBlockId($pageType)
    {
        switch ($pageType) {
            case Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE:
                return $this->getBuffetLandingHeaderBlockId();
            case Tgc_Datamart_Model_Source_LandingPage_Type::EMAIL_VALUE:
                return $this->getEmailLandingHeaderBlockId();
            case Tgc_Datamart_Model_Source_LandingPage_Type::RADIO_VALUE:
                return $this->getRadioLandingHeaderBlockId();
            default:
                return '';
        }
    }

    /**
     * Retrieve landing page default footer block id from config
     *
     * @param integer $pageType
     * @return string
     */
    public function getLandingFooterBlockId($pageType)
    {
        switch ($pageType) {
            case Tgc_Datamart_Model_Source_LandingPage_Type::BUFFET_VALUE:
                return $this->getBuffetLandingFooterBlockId();
            case Tgc_Datamart_Model_Source_LandingPage_Type::EMAIL_VALUE:
                return $this->getEmailLandingFooterBlockId();
            case Tgc_Datamart_Model_Source_LandingPage_Type::RADIO_VALUE:
                return $this->getRadioLandingFooterBlockId();
            default:
                return '';
        }
    }

    /**
     * Retrieve default buffet landing page title from config
     *
     * @return string
     */
    public function getBuffetLandingTitle()
    {
        return Mage::getStoreConfig(self::XML_PATH_BUFFET_LANDING_TITLE);
    }

    /**
     * Retrieve default buffet landing page meta description from config
     *
     * @return string
     */
    public function getBuffetLandingMetaDescription()
    {
        return Mage::getStoreConfig(self::XML_PATH_BUFFET_LANDING_DESCRIPTION);
    }

    /**
     * Retrieve default buffet landing page meta keywords from config
     *
     * @return string
     */
    public function getBuffetLandingMetaKeywords()
    {
        return Mage::getStoreConfig(self::XML_PATH_BUFFET_LANDING_KEYWORDS);
    }

    /**
     * Retrieve default buffet landing page header block id from config
     *
     * @return string
     */
    public function getBuffetLandingHeaderBlockId()
    {
        return Mage::getStoreConfig(self::XML_PATH_BUFFET_LANDING_HEADER_BLOCK_ID);
    }

    /**
     * Retrieve default buffet landing page footer block id from config
     *
     * @return string
     */
    public function getBuffetLandingFooterBlockId()
    {
        return Mage::getStoreConfig(self::XML_PATH_BUFFET_LANDING_FOOTER_BLOCK_ID);
    }

    /**
     * Retrieve buffet landing page default banner mobile image path from config
     *
     * @return string
     */
    public function getBuffetLandingBannerMobileImage()
    {
        $imagePath = Mage::getStoreConfig(self::XML_PATH_BUFFET_LANDING_BANNER_MOBILE_IMAGE);
        if ($imagePath) {
            $imagePath = '/' . $imagePath;
        }
        return $imagePath;
    }

    /**
     * Retrieve buffet landing page default banner desktop image path from config
     *
     * @return string
     */
    public function getBuffetLandingBannerDesktopImage()
    {
        $imagePath = Mage::getStoreConfig(self::XML_PATH_BUFFET_LANDING_BANNER_DESKTOP_IMAGE);
        if ($imagePath) {
            $imagePath = '/' . $imagePath;
        }
        return $imagePath;
    }

    /**
     * Retrieve default email landing page title from config
     *
     * @return string
     */
    public function getEmailLandingTitle()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_LANDING_TITLE);
    }

    /**
     * Retrieve default email landing page meta description from config
     *
     * @return string
     */
    public function getEmailLandingMetaDescription()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_LANDING_DESCRIPTION);
    }

    /**
     * Retrieve default email landing page meta keywords from config
     *
     * @return string
     */
    public function getEmailLandingMetaKeywords()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_LANDING_TITLE);
    }

    /**
     * Retrieve default email landing page header block id from config
     *
     * @return string
     */
    public function getEmailLandingHeaderBlockId()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_LANDING_HEADER_BLOCK_ID);
    }

    /**
     * Retrieve default email landing page footer block id from config
     *
     * @return string
     */
    public function getEmailLandingFooterBlockId()
    {
        return Mage::getStoreConfig(self::XML_PATH_EMAIL_LANDING_FOOTER_BLOCK_ID);
    }

    /**
     * Retrieve default radio landing page title from config
     *
     * @return string
     */
    public function getRadioLandingTitle()
    {
        return Mage::getStoreConfig(self::XML_PATH_RADIO_LANDING_TITLE);
    }

    /**
     * Retrieve default radio landing page meta description from config
     *
     * @return string
     */
    public function getRadioLandingMetaDescription()
    {
        return Mage::getStoreConfig(self::XML_PATH_RADIO_LANDING_DESCRIPTION);
    }

    /**
     * Retrieve default radio landing page meta keywords from config
     *
     * @return string
     */
    public function getRadioLandingMetaKeywords()
    {
        return Mage::getStoreConfig(self::XML_PATH_RADIO_LANDING_TITLE);
    }

    /**
     * Retrieve default radio landing page header block id from config
     *
     * @return string
     */
    public function getRadioLandingHeaderBlockId()
    {
        return Mage::getStoreConfig(self::XML_PATH_RADIO_LANDING_HEADER_BLOCK_ID);
    }

    /**
     * Retrieve default radio landing page footer block id from config
     *
     * @return string
     */
    public function getRadioLandingFooterBlockId()
    {
        return Mage::getStoreConfig(self::XML_PATH_RADIO_LANDING_FOOTER_BLOCK_ID);
    }

    /**
     * Retrieve default radio landing page media code
     *
     * @return string
     */
    public function getRadioLandingDefaultMediaCode()
    {
        return Mage::getStoreConfig(self::XML_PATH_RADIO_LANDING_DEFAULT_MEDIA_CODE);
    }
}
