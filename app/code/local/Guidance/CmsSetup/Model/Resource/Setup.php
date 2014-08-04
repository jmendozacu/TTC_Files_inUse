<?php
/**
 * Setup model
 *
 * Adds functionality for update pages from file system
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     CmsSetup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_CmsSetup_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup
{
    /**
     * Run data install scripts
     *
     * @param string $newVersion
     * @return Mage_Core_Model_Resource_Setup
     */
    protected function _installData($newVersion)
    {
        parent::_installData($newVersion);
        $this->_upgradeCmsContent();
        return $this;
    }

    /**
     * Run data upgrade scripts
     *
     * @param string $oldVersion
     * @param string $newVersion
     * @return Mage_Core_Model_Resource_Setup
     */
    protected function _upgradeData($oldVersion, $newVersion)
    {

        parent::_upgradeData($oldVersion, $newVersion);
        $this->_upgradeCmsContent();
        return $this;
    }

    /**
     * Upgrades CMS content
     */
    protected function _upgradeCmsContent()
    {
        $this->_upgradeBlocks();
        $this->_upgradePages();
    }

    /**
     * Changes page identifier (for all stores)
     *
     * @param string $from Current identifier
     * @param string $to Target identifier
     * @return int Count of changed pages
     */
    public function changePageIdentifier($from, $to)
    {
        $this->getConnection()
            ->update(
                $this->getTable('cms/page'),
                array('identifier' => $to),
                $this->getConnection()->quoteInto('identifier = ?', $from)
            );

        return $this;
    }

    /**
     * Loads all blocks from content/blocks folder and tries to update or create
     * appropriate CMS blocks
     *
     * @throws DomainException If block file does not have ID header
     */
    protected function _upgradeBlocks()
    {
        // Directory is optional, return if not exists
        $blocksDirectory = $this->_getContentDirectory('blocks');
        if (!file_exists($blocksDirectory)) {
            return;
        }

        $blocks = Mage::getModel('cmssetup/files', $blocksDirectory);
        foreach ($blocks as $fileInfo) {
            $blockFile = Mage::getModel('cmssetup/file_block', $fileInfo->getPathname());
            if (!$blockFile->getId()) {
                throw new DomainException("Block '{$fileInfo->getPathname()}' does not have id header.");
            }

            $block = Mage::getModel('cms/block');
            $storeIds = $this->_mapStoreCodesToIds($blockFile->getStores());

            foreach ($storeIds as $storeId) {
                $block = $this->_loadBlock($blockFile->getId(), $storeId);
                if (!$block->isObjectNew()) {
                    break;
                }
            }

            $comparator = Mage::getSingleton('cmssetup/comparator');

            if (!$comparator->isBlockTheSameAsFile($block, $blockFile)) {
                $block->setTitle($blockFile->getTitle())
                    ->setIdentifier($blockFile->getId())
                    ->setIsActive($blockFile->isEnabled() ? 1 : 0)
                    ->setContent($blockFile->getContent())
                    ->setStores($storeIds)
                    ->setHash($comparator->getBlockHash($blockFile))
                    ->save();
            }
        }
    }

    /**
     * Loads all pages from content/blocks folder and tries to update or create
     * appropriate CMS pages
     *
     * @throws DomainException If page does not have ID header
     */
    protected function _upgradePages()
    {
        // Directory is optional, return if not exists
        $pagesDirectory = $this->_getContentDirectory('pages');
        if (!file_exists($pagesDirectory)) {
            return;
        }

        $pages = Mage::getModel('cmssetup/files', $pagesDirectory);
        foreach ($pages as $fileInfo) {
            $pageFile = Mage::getModel('cmssetup/file_page', $fileInfo->getPathname());
            if (!$pageFile->getId()) {
                throw new DomainException("Page '{$fileInfo->getPathname()}' does not have id header.");
            }

            $page = Mage::getModel('cms/page');
            $storeIds = $this->_mapStoreCodesToIds($pageFile->getStores());

            foreach ($storeIds as $storeId) {
                $page = $this->_loadPage($pageFile->getId(), $storeId);
                if (!$page->isObjectNew()) {
                    break;
                }
            }

            $comparator = Mage::getSingleton('cmssetup/comparator');

            if (!$comparator->isPageTheSameAsFile($page, $pageFile)) {
                try {
                    $page->setTitle($pageFile->getTitle())
                        ->setIdentifier($pageFile->getId())
                        ->setStores($storeIds)
                        ->setIsActive($pageFile->isPublished() ? 1 : 0)
                        ->setContentHeading($pageFile->getContentHeading())
                        ->setContent($pageFile->getContent())
                        ->setRootTemplate($pageFile->getLayout())
                        ->setLayoutUpdateXml($pageFile->getLayoutUpdate())
                        ->setHash($comparator->getPageHash($pageFile))
                        ->save();
                } catch (Mage_Core_Exception $e) {
                    if ($e->getMessage() == 'A page URL key for specified store already exists.') {
                        throw new Mage_Core_Exception(
                            "cms_page table is inconsistent; please delete all '{$pageFile->getId()}' pages."
                        );
                    } else {
                        throw $e;
                    }
                }
            }
        }
    }

    /**
     * Returns store IDs that corresponds given store codes
     *
     * @param array $codes Store codes
     * @return array<int> Store IDs
     */
    private function _mapStoreCodesToIds(array $codes)
    {
        if (in_array('admin', $codes)) {
            return array(Mage_Core_Model_App::ADMIN_STORE_ID);
        }

        return array_filter(
            array_map(
                function ($code) {
                    return Mage::getModel('core/store')
                        ->load($code)
                        ->getId();
                },
                $codes
            )
        );
    }

    /**
     * Loads page by identifier from store
     *
     * If cannot load returns new page
     *
     * @param string $identifier Identifier
     * @param integer $storeId Store ID
     * @return Mage_Cms_Model_Page
     */
    protected function _loadPage($identifier, $storeId)
    {
        $page = Mage::getModel('cms/page');
        $resource = Mage::getResourceSingleton('cms/page');
        $pageId = $resource->checkIdentifier($identifier, $storeId, false);

        if ($pageId && !($this->_isDefaultPage($pageId) && $storeId != Mage_Core_Model_App::ADMIN_STORE_ID)) {
            $page->load($pageId);
        }

        return $page;
    }

    /**
     * Returns true if page belongs to default (admin) store
     *
     * @param int $pageId
     * @return boolean
     */
    private function _isDefaultPage($pageId)
    {
        $resource = Mage::getResourceSingleton('cms/page');

        return in_array(Mage_Core_Model_App::ADMIN_STORE_ID, $resource->lookupStoreIds($pageId));
    }

    /**
     * Loads block by identifier from store
     *
     * If cannot load returns new block
     *
     * @param string $identifier Identifier
     * @param integer $storeId Store ID
     * @return Mage_Cms_Model_Block
     */
    protected function _loadBlock($identifier, $storeId)
    {
        return Mage::getResourceModel('cms/block_collection')
            ->addStoreFilter($storeId, false)
            ->addFieldToFilter('identifier', $identifier)
            ->setPageSize(1)
            ->getFirstItem();
    }

    /**
     * Get module name running setup resource
     *
     * @return string
     */
    protected function _getModuleName()
    {
        if (!isset($this->_resourceConfig->setup->module)) {
            Mage::throwException('The "module" configuration node is required in your resource definition');
        }
        return (string) $this->_resourceConfig->setup->module;
    }

    /**
     * Get content directory path from module folder
     *
     * @param string $dir
     * @return string
     */
    protected function _getContentDirectory($dir)
    {
        return Mage::getModuleDir(null, $this->_getModuleName()) . DS . 'content' . DS . $dir;
    }
}
