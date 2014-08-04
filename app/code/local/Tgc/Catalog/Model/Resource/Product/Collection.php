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
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * Product collection
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tgc_Catalog_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    protected $_onlyUseIterativeKeys = false;

    public function useOnlyIterativeKeys($setting = true)
    {
        if(is_bool($setting)) {
            $this->_onlyUseIterativeKeys = $setting;
        }

        return $this;
    }

    protected function _getSelectCountSql($select = null, $resetLeftJoins = true)
    {
        $this->_renderFilters();
        $countSelect = (is_null($select)) ?
            $this->_getClearSelect() :
            $this->_buildClearSelect($select);

        if (Mage::app()->getFrontController()->getRequest()->getModuleName() == 'digital-library') {
            $countSelect->reset(Zend_Db_Select::GROUP);
        }
        $countSelect->columns('COUNT(DISTINCT e.entity_id)');
        if ($resetLeftJoins) {
            $countSelect->resetJoinLeft();
        }
        return $countSelect;
    }

    public function _loadEntities($printQuery = false, $logQuery = false)
    {
        $entity = $this->getEntity();

        if ($this->_pageSize) {
            $this->getSelect()->limitPage($this->getCurPage(), $this->_pageSize);
        }

        $this->printLogQuery($printQuery, $logQuery);

        try {
            /**
             * Prepare select query
             * @var string $query
             */
            $query = $this->_prepareSelect($this->getSelect());
            $rows = $this->_fetchAll($query);
        } catch (Exception $e) {
            Mage::printException($e, $query);
            $this->printLogQuery(true, true, $query);
            throw $e;
        }

        foreach ($rows as $v) {
            $object = $this->getNewEmptyItem()
                ->setData($v);
            try {
                if(!$this->_onlyUseIterativeKeys) {
                    $this->addItem($object);
                } else {
                    $this->addItemCustom($object);
                }
            } catch (Exception $e) {

            }
            if (isset($this->_itemsById[$object->getId()])) {
                $this->_itemsById[$object->getId()][] = $object;
            } else {
                $this->_itemsById[$object->getId()] = array($object);
            }
        }

        return $this;
    }

    /**
     * Function makes it so that whenever an item is added to the array.  The value of the key is NOT the item_id. Instead this function
     * makes value of the key one greater than value of previous key.
     *
     * @param Varien_Object $object
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function addItemCustom(Varien_Object $object)
    {
        if (get_class($object) !== $this->_itemObjectClass) {
            throw Mage::exception('Mage_Eav', Mage::helper('eav')->__('Attempt to add an invalid object'));
        }

        $this->_addItem($object);

        return $this;
    }

    /**
     * Retrive all ids for collection
     * When this collection calls thsi function, it will fail causing entire content block not to render, unless the group part is redeclared.
     *
     * @param unknown_type $limit
     * @param unknown_type $offset
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        $idFieldText = 'e.' . $this->getEntity()->getIdFieldName();
        $idsSelect = $this->_getClearSelect();
        $idsSelect->columns('e.' . $this->getEntity()->getIdFieldName());
        $idsSelect->limit($limit, $offset);
        $idsSelect->resetJoinLeft();
        $idsSelect->setPart('group', array(0 => $idFieldText));

        return $this->getConnection()->fetchCol($idsSelect, $this->_bindParams);
    }
}
