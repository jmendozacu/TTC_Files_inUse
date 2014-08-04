<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Helper_EmailLanding_Banner_Image extends Mage_Core_Helper_Abstract
{
    const MEDIA_PATH  = 'landing_banner';
    const RESIZE_PATH = 'landing_banner/cache';

    protected $_imageProcessor;
    protected $_imageFieldData;

    protected $_resizeWidth;
    protected $_resizeHeight;

    protected $_defaultImages = array();

    /**
     * Default image setter
     *
     * @param string $imagePath
     * @return \Tgc_Datamart_Helper_EmailLanding_Banner_Image
     */
    public function setDefaultImage($field, $imagePath)
    {
        $this->_defaultImages[$field] = $imagePath;
        return $this;
    }

    /**
     * Default image getter
     *
     * @param string $field
     * @return string
     */
    public function getDefaultImage($field)
    {
        return isset($this->_defaultImages[$field]) ? $this->_defaultImages[$field] : '';
    }

    /**
     * Generate image url
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->_imageProcessor) {
            $baseUrl = Mage::getBaseUrl('media');
            try {
                if ($this->_resizeWidth || $this->_resizeHeight) {
                    $newImagePath = Mage::getBaseDir('media') . DS . self::RESIZE_PATH
                        . DS . $this->_resizeWidth . 'x' . $this->_resizeHeight . $this->_imageFieldData;
                    if (!file_exists($newImagePath)) {
                        $this->_imageProcessor->resize($this->_resizeWidth, $this->_resizeHeight);
                        $this->_imageProcessor->save($newImagePath);
                    }

                    return $baseUrl . self::RESIZE_PATH . '/' . $this->_resizeWidth . 'x' . $this->_resizeHeight . $this->_imageFieldData;
                } else {
                    return $baseUrl . self::MEDIA_PATH . $this->_imageFieldData;
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        return '';
    }

    /**
     * Initialize image processor
     *
     * @param Tgc_Datamart_Model_EmailLanding_Banner|string $banner
     * @param type $field
     * @return \Tgc_Datamart_Helper_EmailLanding_Banner_Image
     */
    public function init($banner, $field = 'desktop_image')
    {
        $this->reset();
        if ($banner instanceof Tgc_Datamart_Model_EmailLanding_Banner) {
            $fieldData = $banner->getData($field);
            if (!$fieldData) {
                $fieldData = $this->getDefaultImage($field);
            }
        } else {
            $fieldData = $banner;
        }
        if (!$fieldData) {
            return $this;
        }

        $path = Mage::getBaseDir('media') . DS . self::MEDIA_PATH . DS . $fieldData;
        if (!file_exists($path)) {
            return $this;
        }

        $this->_imageFieldData = $fieldData;

        $this->_imageProcessor = new Varien_Image($path);
        $this->_imageProcessor->keepAspectRatio(true);
        $this->_imageProcessor->keepTransparency(true);
        $this->_imageProcessor->keepFrame(false);

        return $this;
    }

    /**
     *
     * @param type $width
     * @param type $height
     * @return \Tgc_Datamart_Helper_EmailLanding_Banner_Image
     */
    public function resize($width, $height = null)
    {
        $this->_resizeWidth  = $width;
        $this->_resizeHeight = $height;
        return $this;
    }

    /**
     * Reset settings
     *
     * @return \Tgc_Datamart_Helper_EmailLanding_Banner_Image
     */
    public function reset()
    {
        $this->_imageProcessor = null;
        $this->_imageFieldData = null;
        $this->_resizeHeight   = null;
        $this->_resizeWidth    = null;
        return $this;
    }
}
