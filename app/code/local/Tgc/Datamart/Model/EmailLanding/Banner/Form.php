<?php
/**
 *
 * @author     Guidance Magento Team <magento@guidance.com>
 * @category   Tgc
 * @package    DataMart
 * @copyright  Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Model_EmailLanding_Banner_Form
{
    /** @var \Tgc_Datamart_Model_EmailLanding_Banner */
    protected $_banner;

    /**
     * @var Varien_File_Uploader
     */
    protected $_mobileImageUploader;

    /**
     * @var Varien_File_Uploader
     */
    protected $_desktopImageUploader;

    /**
     * Initialize form
     *
     */
    public function __construct(Tgc_Datamart_Model_EmailLanding_Banner $banner)
    {
        $this->_banner = $banner;
    }

    /**
     * Validate form data
     *
     * @param array $data
     * @return array
     */
    public function validateData(array $data)
    {
        $errors = array();

        if (!isset($data['title']) || !Zend_Validate::is($data['title'], 'NotEmpty')) {
            $errors[] = $this->_getHelper()->__('Title is required.');
        }

        if (!isset($data['ad_codes']) || !is_array($data['ad_codes'])) {
            $errors[] = $this->_getHelper()->__('Ad Codes are required.');
        } else {
            // TODO validate ad codes
        }

        if (!$this->_getMobileImageUploader() && !$this->_banner->getMobileImage()) {
            $errors[] = $this->_getHelper()->__('Mobile Image file is required.');
        }

        if (!$this->_getDesktopImageUploader() && !$this->_banner->getDesktopImage()) {
            $errors[] = $this->_getHelper()->__('Desktop Image file is required.');
        }

        return $errors;
    }

    /**
     * Compact form data
     *
     * @param array $data
     * @return Tgc_Datamart_Model_EmailLanding_Banner_Form
     */
    public function compactData(array $data)
    {
        $this->_banner->addData($data);
        if ($this->_getMobileImageUploader()) {
            $this->_banner->setMobileImage($this->_saveImageFile($this->_getMobileImageUploader()));
        }
        if ($this->_getDesktopImageUploader()) {
            $this->_banner->setDesktopImage($this->_saveImageFile($this->_getDesktopImageUploader()));
        }
        return $this;
    }

    /**
     * Save image
     *
     * @param Varien_File_Uploader $uploader
     * @return type
     * @throws LogicException
     */
    protected function _saveImageFile(Varien_File_Uploader $uploader)
    {
        $uploader->setAllowedExtensions(array('jpg', 'png', 'jpeg', 'gif'))
            ->setAllowCreateFolders(true)
            ->setAllowRenameFiles(true)
            ->setFilesDispersion(true);

        $result = $uploader->save(
            Mage::getBaseDir('media') . DS . Tgc_Datamart_Helper_EmailLanding_Banner_Image::MEDIA_PATH
        );

        if (empty($result['file'])) {
            throw new LogicException("Cannot save image.");
        }

        return $result['file'];
    }

    /**
     * @return false|Varien_File_Uploader
     */
    protected function _getMobileImageUploader()
    {
        if (is_null($this->_mobileImageUploader)) {
            try {
                $this->_mobileImageUploader = new Varien_File_Uploader('mobile_image');
            } catch (Exception $e) {
                $this->_mobileImageUploader = false;
            }
        }

        return $this->_mobileImageUploader;
    }

    /**
     * @return false|Varien_File_Uploader
     */
    protected function _getDesktopImageUploader()
    {
        if (is_null($this->_desktopImageUploader)) {
            try {
                $this->_desktopImageUploader = new Varien_File_Uploader('desktop_image');
            } catch (Exception $e) {
                $this->_desktopImageUploader = false;
            }
        }

        return $this->_desktopImageUploader;
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function _getHelper()
    {
        return Mage::helper('tgc_datamart');
    }
}
