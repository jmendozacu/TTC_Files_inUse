<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Tgc_SiteMap
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SiteMap_Model_Resource_Page extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Init resource model (cms/page)
     */
    protected function _construct()
    {
        $this->_init('cms/page', 'page_id');
    }

    /**
     * Retrieve cms page collection array
     *
     * @param unknown_type $storeId
     * @return array
     */
    public function getCollection($storeId)
    {
        $pages = array();
        
        $select = $this->_getWriteAdapter()->select()
            ->from(
                array('main_table' => $this->getMainTable()),
                array($this->getIdFieldName(),
                'identifier AS url', 'title')
            )
            ->join(
                array('store_table' => $this->getTable('cms/page_store')),
                'main_table.page_id=store_table.page_id',
                array()
            )
            ->where('main_table.is_active=1')
            ->where('store_table.store_id IN(?)', array(0, $storeId))
            ->order('title asc');
        $query = $this->_getWriteAdapter()->query($select);
        while ($row = $query->fetch()) {
            if (in_array($row['url'], array(Mage_Cms_Model_Page::NOROUTE_PAGE_ID, 'service-unavailable'))) {
                continue;
            }
            $page = $this->_prepareObject($row);
            $pages[$page->getUrl()] = $page;
        }
        
        return $pages;
    }

    /**
     * Prepare page object
     *
     * @param array $data
     * @return Varien_Object
     */
    protected function _prepareObject(array $data)
    {
        $object = new Varien_Object();
        $object->setId($data[$this->getIdFieldName()]);
        $object->setUrl($data['url']);
        $object->setTitle($data['title']);
        
        return $object;
    }
}
