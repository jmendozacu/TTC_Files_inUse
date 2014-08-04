<?php
/**
 * Factory, which creates prefix processors
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Urlrewrite_PrefixProcessor_Factory
{
    private $_processors;

    /**
     * @return array of Tgc_Catalog_Model_Urlrewrite_PrefixProcessor_Interface
     */
    public function getAllPrefixProcessors()
    {
        if (is_null($this->_processors)) {
            $this->_processors = array();
            $urlPrefix = Mage::getConfig()->getNode('tgc_catalog/url_prefix');
            if ($urlPrefix) {
                foreach ($urlPrefix->children() as $code => $model) {
                    $modelName = (string)$model;
                    if ($modelName) {
                        $this->_processors[$code] = Mage::getModel($modelName);
                    }
                }
            }
        }
        return $this->_processors;
    }
}