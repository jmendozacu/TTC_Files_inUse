<?php
/*
<!--
  - Guidance Magento Team <magento@guidance.com>
  - Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
  -->
 */

class Guidance_CmsSetup_Model_Resource_Page extends Mage_Cms_Model_Resource_Page
{
    /**
     * Override to allow option of returning disabled pages
     *
     * @param string $identifier
     * @param int $storeId
     * @param int $active make null to select active and inactive
     * @return int
     */
    public function checkIdentifier($identifier, $storeId, $activeOnly = true)
    {
        if ($activeOnly) {
            return parent::checkIdentifier($identifier, $storeId);
        }

        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_getLoadByIdentifierSelect($identifier, $stores);
        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('cp.page_id')
            ->order('cps.store_id DESC')
            ->limit(1);

        return $this->_getReadAdapter()->fetchOne($select);
    }
}















