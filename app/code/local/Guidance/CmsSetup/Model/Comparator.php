<?php
/**
 * Comparator model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     CmsSetup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_CmsSetup_Model_Comparator
{
    const CONFIG_PATH_FORCE_UPDATE = 'cmssetup/force_update';

    /**
     * Returns true if block model is the same as file or hash is null (it means that
     * block was modified from admin panel)
     *
     * @param Mage_Cms_Model_Block $block
     * @param Guidance_CmsSetup_Model_File_Block $blockFile
     * @return boolean
     */
    public function isBlockTheSameAsFile(Mage_Cms_Model_Block $block, Guidance_CmsSetup_Model_File_Block $blockFile)
    {
        return $this->_isUpdateForbidden($block) || $block->getHash() == $this->getBlockHash($blockFile);
    }

    /**
     * Returns true if page model is the same as file or hash is null (it means that
     * page was modified from admin panel)
     *
     * @param Mage_Cms_Model_Page $page
     * @param Guidance_CmsSetup_Model_File_Page $pageFile
     * @return boolean
     */
    public function isPageTheSameAsFile(Mage_Cms_Model_Page $page, Guidance_CmsSetup_Model_File_Page $pageFile)
    {
        return $this->_isUpdateForbidden($page) || $page->getHash() == $this->getPageHash($pageFile);
    }

    /**
     * Returns block's hash
     *
     * @param Guidance_CmsSetup_Model_File_Block $blockFile
     * @return string
     */
    public function getBlockHash(Guidance_CmsSetup_Model_File_Block $blockFile)
    {
        return $this->_hash($blockFile->getId() . implode('|', $blockFile->getStores()) . $blockFile->getTitle()
                                . $blockFile->isEnabled() . $blockFile->getContent());
    }

    /**
     * Returns page's hash
     *
     * @param Guidance_CmsSetup_Model_File_Page $pageFile
     * @return string
     */
    public function getPageHash(Guidance_CmsSetup_Model_File_Page $pageFile)
    {
        return $this->_hash($pageFile->getId() . implode('|', $pageFile->getStores()) . $pageFile->getLayout()
                                . $pageFile->getContent() . $pageFile->getContentHeading() . $pageFile->isPublished()
                                . $pageFile->getTitle(), $pageFile->getLayoutUpdate());
    }

    /**
     * Return string hash
     *
     * @param string $data
     * @return string
     */
    protected function _hash($data)
    {
        return hash('sha256', $data);
    }

    private function _isUpdateForbidden(Varien_Object $object)
    {
        return $object->getId() !== null && $object->getHash() === null
                    && !Mage::getStoreConfigFlag(self::CONFIG_PATH_FORCE_UPDATE);
    }
}