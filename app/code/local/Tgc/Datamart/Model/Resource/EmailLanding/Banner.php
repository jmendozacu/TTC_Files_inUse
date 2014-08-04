<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Model_Resource_EmailLanding_Banner extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('tgc_datamart/landing_banner', 'banner_id');
    }

    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Tgc_Datamart_Model_Resource_EmailLanding_Banner
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $adCodes = $this->loadAdCodes($object->getId());
            $object->setData('ad_codes', $adCodes);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Perform operations after object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Tgc_Datamart_Model_Resource_EmailLanding_Banner
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldAdCodes = $this->loadAdCodes($object->getId());
        $newAdCode = (array)$object->getAdCodes();

        $table  = $this->getTable('tgc_datamart/landing_banner_adcode');
        $insert = array_diff($newAdCode, $oldAdCodes);
        $delete = array_diff($oldAdCodes, $newAdCode);

        if ($delete) {
            $where = array(
                'banner_id = ?'  => (int) $object->getId(),
                'ad_code IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $adCode) {
                $data[] = array(
                    'banner_id'  => (int) $object->getId(),
                    'ad_code' => (int) $adCode
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Get banner ad codes
     *
     * @param int $id
     * @return array
     */
    public function loadAdCodes($id)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('tgc_datamart/landing_banner_adcode'), 'ad_code')
            ->where('banner_id = :banner_id');

        $binds = array(
            ':banner_id' => (int) $id
        );

        return $adapter->fetchCol($select, $binds);
    }

    /**
     * Load banner data by ad code
     *
     * @param Tgc_Datamart_Model_EmailLanding_Banner $object
     * @param integer $adCode
     * @return boolean
     */
    public function loadByAdCode(Tgc_Datamart_Model_EmailLanding_Banner $object, $adCode)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select()->from(array('banner' => $this->getMainTable()))
            ->joinInner(
                array('adcode' => $this->getTable('tgc_datamart/landing_banner_adcode')),
                'adcode.banner_id = banner.banner_id',
                array())
            ->order('banner_id ' . Zend_Db_Select::SQL_DESC)
            ->where('ad_code = ?', $adCode)
            ->limit(1);

        $data = $read->fetchRow($select);

        if (!$data) {
            return false;
        }

        $object->setData($data);

        $this->_afterLoad($object);
        return true;
    }
}
