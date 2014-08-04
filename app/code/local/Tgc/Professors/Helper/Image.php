<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Professors_Helper_Image extends Mage_Core_Helper_Abstract
{
    const MEDIA_PATH  = 'professor';
    const RESIZE_PATH = 'professor/cache';

    protected $_imageProcessor;
    protected $_skinPlaceholder;
    protected $_imageFieldData;

    protected $_resizeWidth;
    protected $_resizeHeight;

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
                return Mage::getDesign()->getSkinUrl($this->_skinPlaceholder);
            }
        } else {
            return Mage::getDesign()->getSkinUrl($this->_skinPlaceholder);
        }
    }

    /**
     * Set placeholder image, expects relative skin path as a parameter
     *
     * @param type $skinPath
     * @return \Tgc_Professors_Helper_Image
     */
    public function setSkinPlaceholder($skinPath)
    {
        $this->_skinPlaceholder = $skinPath;
        return $this;
    }

    /**
     * Initialize image processor
     *
     * @param Tgc_Professors_Model_Professor $professor
     * @param type $field
     * @return \Tgc_Professors_Helper_Image
     */
    public function init($professor, $field = 'photo')
    {
        $this->reset();
        if ($professor instanceof Tgc_Professors_Model_Professor) {
            $fieldData = $professor->getData($field);
        } else {
            $fieldData = $professor;
        }
        if (!$fieldData) {
            return $this;
        }

        $path = Mage::getBaseDir('media') . DS . self::MEDIA_PATH . DS . $fieldData;
        if (!file_exists($path)) {
            return $this;
        }

        $this->_imageFieldData = $fieldData;

        try {
            $this->_imageProcessor = new Varien_Image($path);
            $this->_imageProcessor->keepAspectRatio(true);
            $this->_imageProcessor->keepTransparency(true);
            $this->_imageProcessor->keepFrame(false);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }

    /**
     *
     * @param type $width
     * @param type $height
     * @return \Tgc_Professors_Helper_Image
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
     * @return \Tgc_Professors_Helper_Image
     */
    public function reset()
    {
        $this->_imageProcessor = null;
        $this->_imageFieldData = null;
        $this->_resizeHeight   = null;
        $this->_resizeWidth    = null;
        $this->setSkinPlaceholder('images/tgc/Prof_Absent_Image_Male.jpg');
        return $this;
    }
}
