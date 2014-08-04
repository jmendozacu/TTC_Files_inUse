<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_EmailLanding_Banner extends Mage_Core_Block_Template
{
    /**
     * Banner object
     *
     * @var Tgc_Datamart_Model_EmailLanding_Banner
     */
    protected $_banner;

    /**
     * Image helper
     *
     * @var Tgc_Datamart_Helper_EmailLanding_Banner_Image
     */
    protected $_imageHelper;

    /**
     * Retrieve banner object
     *
     * @return Tgc_Datamart_Model_EmailLanding_Banner
     */
    public function getBanner()
    {
        if (is_null($this->_banner)) {
            $this->_banner = Mage::getModel('tgc_datamart/emailLanding_banner');
            $adCode = $this->getAdCode();
            if (!is_null($adCode)) {
                $this->_banner->loadByAdCode($adCode);
            }
        }

        return $this->_banner;
    }

    /**
     * Ad Code getter
     *
     * @return string
     */
    public function getAdCode()
    {
        if (!$this->hasData('ad_code')) {
            $this->setAdCode(Mage::registry('ad_code'));
        }

        return $this->getData('ad_code');
    }

    /**
     * Retrieve image helper
     *
     * @return Tgc_Datamart_Helper_EmailLanding_Banner_Image
     */
    public function getImageHelper()
    {
        if (is_null($this->_imageHelper)) {
            $this->_imageHelper = Mage::helper('tgc_datamart/emailLanding_banner_image');
            if ($this->hasDefaultMobileImage()) {
                $this->_imageHelper->setDefaultImage('mobile_image', $this->getDefaultMobileImage());
            }
            if ($this->hasDefaultDesktopImage()) {
                $this->_imageHelper->setDefaultImage('desktop_image', $this->getDefaultDesktopImage());
            }
        }

        return $this->_imageHelper;
    }
}
