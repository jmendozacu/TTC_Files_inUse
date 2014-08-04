<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_ImportExport
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * ImportExport import data resource model
 *
 * @category    Mage
 * @package     Mage_ImportExport
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tgc_Dax_Model_Resource_Import_Data extends Mage_ImportExport_Model_Resource_Import_Data
{
    protected  $_entity_type_code;

    /**
     * Retrieve an external iterator
     *
     * @return IteratorIterator
     */
    public function getIterator()
    {
        $adapter = $this->_getWriteAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), array('data'))
            ->where('entity = ?', $this->getEntityTypeCode())
            ->order('id ASC');
        $stmt = $adapter->query($select);

        $stmt->setFetchMode(Zend_Db::FETCH_NUM);
        if ($stmt instanceof IteratorAggregate) {
            $iterator = $stmt->getIterator();
        } else {
            // Statement doesn't support iterating, so fetch all records and create iterator ourself
            $rows = $stmt->fetchAll();
            $iterator = new ArrayIterator($rows);
        }

        return $iterator;
    }

    /**
     * Clean all bunches from table.
     *
     * @return Varien_Db_Adapter_Interface
     */
    public function cleanBunches()
    {
        return $this->_getWriteAdapter()->delete($this->getMainTable(), array('entity = ?' => $this->getEntityTypeCode()));
    }

    /**
     * Return behavior from import data table.
     *
     * @throws Exception
     * @return string
     */
    public function getBehavior()
    {
        $adapter = $this->_getReadAdapter();
        $behaviors = array_unique($adapter->fetchCol(
            $adapter->select()
                ->from($this->getMainTable(), array('behavior'))
                ->where('entity = ?', $this->getEntityTypeCode())
        ));
        if(count($behaviors) === 0) {
            throw new Tgc_Dax_Exception_Import_NoData(Mage::helper('importexport')->__('Import stopped because file contains no data'));
        } elseif (count($behaviors) != 1) {
            Mage::throwException(Mage::helper('importexport')->__('Error in data structure: behaviors are mixed'));
        }
        return $behaviors[0];
    }

    /**
     * Get next bunch of validated rows.
     *
     * @return array|null
     */
    public function getNextBunch()
    {
        if (null === $this->_iterator) {
            $this->_iterator = $this->getIterator();
            $this->_iterator->rewind();
        }
        if ($this->_iterator->valid()) {
            $dataRow = $this->_iterator->current();
            $dataRow = Mage::helper('core')->jsonDecode($dataRow[0]);
            $this->_iterator->next();
        } else {
            $this->_iterator = null;
            $dataRow = null;
        }
        return $dataRow;
    }

    public function setEntityTypeCode($type)
    {
        $this->_entity_type_code = $type;
    }

    public function getEntityTypeCode()
    {
        return $this->_entity_type_code;
    }
}
