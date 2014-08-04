<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Setup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_Setup_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Setup
{
    private $_realStoreId;

    private $_afterAllUpdatesCallbacks = array();

    /**
     * Adds call back that will be called after all updates
     *
     * Some modules, like Enterprise_SalesArchive, create own data structures
     * on apply after all updates call. So you cannot rely onto this structure
     * in SQL of data upgrades. With this method you can add callback from upgrade
     * script that will work after call back of module structures of that you
     * want to alter. (The calls respects modules dependencies.)
     *
     * @param callable $callback Callback to be called
     * @throws InvalidArgumentException If not callable callbacj passed
     * @return Guidance_Setup_Model_Resource_Setup Self
     */
    public function addAfterApplyAllUpdatesCall($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Callback should be callable.');
        }

        $this->_afterAllUpdatesCallbacks[] = $callback;
        $this->_callAfterApplyAllUpdates = true;

        return $this;
    }

    /**
     * This method will be called after all updates
     * (!) Do not use it dirrectly
     */
    public function afterApplyAllUpdates()
    {
        foreach ($this->_afterAllUpdatesCallbacks as $callback) {
            call_user_func($callback);
        }
    }

    /**
     * Sets admin store ID as current
     *
     * We need to do this to create atrtibute values in admin store on
     * initial installation. Because on initial installation current
     * store ID is 1.
     */
    public function startSetup()
    {
        parent::startSetup();

        // We need to do this to create atrtibute values in admin store on initial installation
        $this->_realStoreId = Mage::app()->getStore()->getId();
        Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);

        if (!Mage::registry('isSecureArea')) {
            Mage::register('isSecureArea', true);
        }
    }

    /**
     * Rollback current store ID
     */
    public function endSetup()
    {
        parent::endSetup();

        Mage::app()->getStore()->setId($this->_realStoreId);
    }

    /**
     * Creates category
     *
     * @param string $name Name
     * @param Mage_Catalog_Model_Category $parent Parent category
     * @param int|Mage_Core_Model_Store $store Store
     * @param bool $active Is active
     * @param bool $includeInMenu Include in top menu
     * @return Mage_Catalog_Model_Category Category created
     */
    public function addCategory($name, Mage_Catalog_Model_Category $parent = null,
        $store = Mage_Core_Model_App::ADMIN_STORE_ID, $active = true, $includeInMenu = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = $store->getId();
        }

        $category = Mage::getModel('catalog/category');
        $category->setName($name)
            ->setUrlKey($category->formatUrlKey($name))
            ->setPath($parent ? $parent->getPath() : '1')
            ->setStoreId($store)
            ->setIsActive($active)
            ->setIncludeInMenu($includeInMenu);

        try {
            $category->save();
        } catch (Mage_Eav_Model_Entity_Attribute_Exception $e) {
            // For 'Category with the 'accessories' url_key attribute already exists' of save
            $urlKey = ($parent)
                ? $parent->getUrlKey() . '-' . $category->getUrlKey()
                : $category->getUrlKey() . '-' . uniqid();
            try {
                $category->setUrlKey($urlKey)
                    ->save();
            } catch (Exception $e) {
                $category->load($name, 'name');
            }
        } catch (Exception $e) {
            // For '$_FILES array is empty' on after save
            if (!strpos($e, '$_FILES array is empty')) {
                Mage::logException($e);
            }
        }

        return $category;
    }

    /**
     * Returns category by name or ID
     *
     * @param int|string $id Category ID or name
     * @return Mage_Catalog_Model_Category
     * @throws InvalidArgumentException If cannot load catgeory
     */
    public function getCategory($id)
    {
        $category = Mage::getModel('catalog/category');

        if (is_integer($id)) {
            $category->load($id);
        } else {
            $category = $category->loadByAttribute('name', $id);
        }

        if ($category->isObjectNew()) {
            throw new InvalidArgumentException("Unable to load catgeory '$id'");
        }

        return $category;
    }

    /**
     * Creates CMS static block
     *
     * @param string $id Identifier
     * @param string $title Title
     * @param string $content Content
     * @param boolean $isActive Is block active
     * @return Mage_Cms_Model_Block
     */
    public function addCmsBlock($id, $title, $content, $isActive = true, $stores = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        return Mage::getModel('cms/block')
            ->setTitle($title)
            ->setIdentifier($id)
            ->setIsActive($isActive)
            ->setContent($content)
            ->setStores($stores)
            ->save();
    }

    /**
     * Returns block by identifier
     *
     * @param string $id Identifier
     * @throws InvalidArgumentException If block with given ID does not exist
     * @return Mage_Core_Model_Block
     */
    public function getCmsBlock($id)
    {
        $block = Mage::getModel('cms/block')
            ->load($id, 'identifier');

        if (!$block->getId()) {
            throw new InvalidArgumentException("Block $id does not exist.");
        }

        return $block;
    }

    /**
     * Sets value of config option by path and scope
     *
     * @param string $path Path
     * @param string $value Value
     * @param Mage_Core_Model_Website|Mage_Core_Model_Website_Store|string $scope Scope
     * @param integer $scopeId If scope is string ID defines scope ID
     * @param integer $inherit
     * @return Click2shop_Setup_Model_Resource_Setup
     */
    public function setConfigData($path, $value, $scope = 'default', $scopeId = 0, $inherit = 0)
    {
        if (is_string($scope)) {
            $scopeCode = $scope;
        } else if ($scope instanceof Mage_Core_Model_Website) {
            $scopeCode = 'websites';
            $scopeId = $scope->getId();
        } else if ($scope instanceof Mage_Core_Model_Store) {
            $scopeCode = 'stores';
            $scopeId = $scope->getId();
        } else {
            throw new InvalidArgumentException('Invalid scope; should be website, store or string.');
        }

        return parent::setConfigData($path, $value, $scopeCode, $scopeId, $inherit);
    }
    /**
     * Delete value of config option by path and scope
     *
     * @param string $path
     * @param string $scope
     * @return Mage_Core_Model_Resource_Setup
     * @throws InvalidArgumentException
     */
    public function deleteConfigData($path, $scope = 'default')
    {
        if (is_string($scope)) {
            $scopeCode = $scope;
        } else if ($scope instanceof Mage_Core_Model_Website) {
            $scopeCode = 'websites';
        } else if ($scope instanceof Mage_Core_Model_Store) {
            $scopeCode = 'stores';
        } else {
            throw new InvalidArgumentException('Invalid scope; should be website, store or string.');
        }

        return parent::deleteConfigData($path, $scopeCode);
    }

    /**
     * Copies file from media source defined by _getMediaSource()
     * to media + targetDir
     *
     * @param string $fileName In the source directory
     * @param string $targetDir Target directory in the media (if does not exists will be created)
     * @param string $overwrite If true overwrites existent file if false copies to file with unique name
     * @return string Relative path in media to copied file
     * @throws RuntimeException If cannot copy file from source to target
     * @throws DomainException If file does not exist in target
     *                             or source is not writable
     *                             or cannot create tareget in the media
     *                             or cannot overwrite file in thetarget.
     */
    public function deployToMedia($fileName, $targetDir, $overwrite = false)
    {
        $source = $this->_prepareSource($fileName);
        $targetDir = Mage::getBaseDir('media') . DS . trim($targetDir, DS);
        $target = $this->_prepareTarget($targetDir, basename($fileName), $overwrite);

        if (!copy($source, $target)) {
            throw new RuntimeException("Unable to deploy $fileName to $targetDir.");
        }

        Mage::log("Deploy to media: $source ==> $target");

        return $this->_stripMedia($target);
    }

    /**
     * Deploys table rate prices from CSV file
     *
     * Format of CSV file the same as in admin panel
     *
     * @param string $fileName File name
     * @param integer $websiteId Website ID
     * @return Click2shop_Setup_Model_Resource_Setup Self
     */
    public function deployTableRate($fileName, Mage_Core_Model_Website $website, $conditionName = null)
    {
        if (!$conditionName) {
            $conditionName = $website->getConfig('carriers/tablerate/condition_name');
        }
        $config = new Varien_Object(array(
            'scope_id' => $website->getId(),
            'groups' => array(
                'tablerate' => array(
                    'fields' => array(
                        'condition_name' => array(
                            'value' => $conditionName,
                        ),
                    ),
                ),
            ),
        ));
        $_FILES['groups']['tmp_name']['tablerate']['fields']['import']['value'] = $fileName;
        Mage::getResourceModel('shipping/carrier_tablerate')->uploadAndImport($config);

        return $this;
    }

    /**
     * Adds new attribute set and adds attributes from source attribute set to it
     *
     * @param string $name Name
     * @param int $sourceAttributeSetId ID of source attribute set
     * @return Mage_Eav_Model_Entity_Attribute_Set
     */
    public function copyAttributeSetId($name, $sourceAttributeSetId)
    {
        return Mage::getModel('eav/entity_attribute_set')
            ->setEntityTypeId($this->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY))
            ->setAttributeSetName($name)
            ->save()
            ->initFromSkeleton($sourceAttributeSetId)
            ->save();
    }

    /**
     * Returns path to media source directory for deployToMedia()
     *
     * This method can be used to define media sources per module
     *
     * @return string Media source directory name
     */
    protected function _getMediaSource()
    {
        return Mage::getModuleDir(null, 'Guidance_Setup') . DS . 'content';
    }

    private function _prepareSource($fileName)
    {
        $source = rtrim($this->_getMediaSource(), DS) . DS . $fileName;

        if (!file_exists($source)) {
            throw new DomainException("$source does not exist.");
        }
        if (!is_readable($source)) {
            throw new DomainException("$source is not readable.");
        }

        return $source;
    }

    private function _prepareTarget($directory, $fileName, $overwrite)
    {
        $this->_createIfNotExist($directory);
        $target = $directory . DS . $fileName;

        if (file_exists($target)) {
            if ($overwrite) {
                if (!unlink($target)) {
                    throw new DomainException("Cannot overwrite $target.");
                }
            } else {
                $target = $this->_getUniqueFileName($directory, $fileName);
            }
        }

        return $target;
    }

    private function _getUniqueFileName($directory, $fileName)
    {
        for ($i = 1, $f = $directory . DS . $fileName; file_exists($f); $i++) {
            $f = $directory
                . DS . pathinfo($fileName, PATHINFO_FILENAME)
                . "-$i." . pathinfo($fileName, PATHINFO_EXTENSION);
        }

        return $f;
    }

    private function _createIfNotExist($directory)
    {
        $pathParts = explode('/', rtrim($directory, '/'));
        $path = '';

        foreach ($pathParts as $name) {
            $path = ltrim($path . DS . $name, '\\');
            if (!file_exists($path)) {
                if (!mkdir($path)) {
                    throw new DomainException("Cannot create $path directory.");
                }
            }
        }
    }

    private function _stripMedia($media)
    {
        return trim(substr($media, strlen(Mage::getBaseDir('media'))), DS);
    }
}
