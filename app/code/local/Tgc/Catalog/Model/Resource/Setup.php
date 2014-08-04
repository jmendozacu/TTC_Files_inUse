<?php
/**
 * Setup resource
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Resource_Setup extends Guidance_Setup_Model_Resource_Setup
{

    const ATTRIBUTE_POSITION_BEFORE = 'positionbefore';
    const ATTRIBUTE_POSITION_AFTER = 'positionafter';

    public function createCategory(Mage_Catalog_Model_Category $parentCategory, $config)
    {
        $category = Mage::getModel('catalog/category');

        $children = false;

        if (isset($config['children']) && is_array($config['children'])) {
            $children = $config['children'];
            unset($config['children']);
        }

        // Default settings
        $category->setStoreId(0)
            ->setDisplayMode('PRODUCTS')
            ->setAttributeSetId($category->getDefaultAttributeSetId())
            ->setIsActive(1)
            ->setIsAnchor(1)
            ->setIncludeInMenu(0)
            ->setPath($parentCategory->getPath())
            ->setInitialSetupFlag(true);

        $category->addData($config)->save();

        if (isset($children) && is_array($children)) {
            foreach ($children as $child) {
                $this->createCategory($category, $child);
            }
        }
    }

    public function retrieveRowFromAttributeSetTable($entityTypeId, $newAttributeSetName)
    {
        $select = $this->_conn->select()
            ->from($this->getTable('eav/attribute_set'))
            ->where('entity_type_id = :entity_type_id')
            ->where('attribute_set_name = :attribute_set_name');
        $existingSet = $this->_conn->fetchAll($select, array('entity_type_id' => $entityTypeId, 'attribute_set_name' => $newAttributeSetName));

        if(count($existingSet) > 1) {
            Mage::log('A row from the attribute set table could not be retrieved because more than one attribute set shares that same name.');
            $existingSet = false;
        } elseif(count($existingSet) == 1) {
            $existingSet = $existingSet[0];
        } else {
            $existingSet = 0;
        }

        return $existingSet;
    }

    public function removeAttributesFromSet(array $attributesToRemove, $setId = '')
    {
        if($attributesToRemove && $setId) {
            $attributeIdsToRemove = $this->convertArrayAttributeCodesToIds($attributesToRemove);
            $table = Mage::getSingleton('core/resource')->getTableName('eav/entity_attribute');

            $where = array(
                'attribute_id IN(?)'        => $attributeIdsToRemove,
                'attribute_set_id = ?'      => $setId,
            );
            $this->getConnection()->delete($table, $where);
        }
    }

    public function convertArrayAttributeCodesToIds(array $attributeCodes)
    {
        $listAttributeCodesToIds = $this->getConnection()->fetchPairs('SELECT attribute_code, attribute_id FROM eav_attribute');

        $arrayAttributeIds = array();
        foreach($attributeCodes as $attributeCode) {
            if(isset($listAttributeCodesToIds[$attributeCode])) {
                array_push($arrayAttributeIds, $listAttributeCodesToIds[$attributeCode]);
            }
        }

        return $arrayAttributeIds;
    }

    /**
     * Changes an attributes sort order so that it comes before (or after) another attribute
     *
     * @param $masterAttributeCode -this is the attribute whose sort order you would like to change.
     * @param $targetAttributeCode - this function is designed to make the master attribute come before (or after) this attribute.
     * @param string $insertBeforeOrAfter - determines whether master attribute sort order will be changed to come either before, or after, the target attribute.
     */
    public function putAttributeBeforeOrAfterAnotherAttribute($masterAttributeCode, $targetAttributeCode, $insertBeforeOrAfter = self::ATTRIBUTE_POSITION_BEFORE)
    {
        $masterAttributeId = $this->getAttributeIdFromCode($masterAttributeCode);
        $targetAttributeId = $this->getAttributeIdFromCode($targetAttributeCode);

        if($masterAttributeId) {
            $masterAttributeSetIds = $this->getSetIdsAttributeBelongsTo($masterAttributeId);
            foreach($masterAttributeSetIds as $masterAttributeSetId) {
                $positionOfTargetAttribute = $this->getPositionOfAttribute($targetAttributeId, $masterAttributeSetId);
                if($positionOfTargetAttribute) { //target attribute might not exist in every set the master belongs to. if it doesn't, this condition does not run.
                    $positionToIncrementFrom = $this->getPositionToIncrementFrom($positionOfTargetAttribute, $insertBeforeOrAfter);
                    $this->incrementAttributePositions($positionToIncrementFrom, $masterAttributeSetId);
                    $primaryKeyValue = $this->getEavEntityAttributePrimaryKey($masterAttributeId, $masterAttributeSetId);
                    if($primaryKeyValue) {
                        $this->changeAttributeSortOrderValue($primaryKeyValue, $positionToIncrementFrom);
                    }
                }
            }
        }
    }

    /**
     * This looks in eav_entity_attribute table to see all the different sets an attribute belongs to.
     *
     * @param $attributeId
     * @return array|bool
     */
    public function getSetIdsAttributeBelongsTo($attributeId)
    {
        $attributeSetIds = false;
        if($attributeId) {
            $adapter = $this->getConnection();
            $table = $this->getTable('eav/entity_attribute');
            $selectAttributeFromEavEntity = $this->getConnection()->select()
                ->from($table, array('attribute_set_id'))
                ->where($adapter->quoteIdentifier('attribute_id') . '=?', $attributeId);

            $result = $adapter->fetchCol($selectAttributeFromEavEntity);
            if(count($result) > 0) {
                $attributeSetIds = $result;
            } else {
                $attributeSetIds = false;
            }
        }

        return $attributeSetIds;
    }

    /**
     * Grabs the primary key value from eav_entity_attribute table.
     *
     * @param $attributeId
     * @param $attributeSetId
     * @return bool
     */
    public function getEavEntityAttributePrimaryKey($attributeId, $attributeSetId)
    {
        $adapter = $this->getConnection();
        $table = $this->getTable('eav/entity_attribute');
        $selectPrimaryKeyValFromEavEntity = $adapter->select()
            ->from($table, array('entity_attribute_id'))
            ->where($adapter->quoteIdentifier('attribute_id') . '=?', $attributeId)
            ->where($adapter->quoteIdentifier('attribute_set_id') . '=?', $attributeSetId);

        $result = $adapter->fetchCol($selectPrimaryKeyValFromEavEntity);
        if(isset($result[0])) {
            $primaryKeyValue = $result[0];
        } else {
            $primaryKeyValue = false;
        }

        return $primaryKeyValue;
    }

    /**
     * Grabs the position of an attribute from eav_entity_attribute table.  Note, the attribute_id and attribute_set_id are needed. Both these together make a row unique.
     *
     * @param string $attributeId
     * @param string $attributeSetId
     * @return bool
     */
    public function getPositionOfAttribute($attributeId = '', $attributeSetId = '')
    {
        $position = false;
        if($attributeId && $attributeSetId) {
            $adapter = $this->getConnection();
            $table = $this->getTable('eav/entity_attribute');

            $selectEntityAttributeRow = $adapter->select()
                ->from($table,array('sort_order'))
                ->where($adapter->quoteIdentifier('attribute_set_id') . '=?', $attributeSetId)
                ->where($adapter->quoteIdentifier('attribute_id') . '=?', $attributeId);

            $result = $adapter->fetchCol($selectEntityAttributeRow);

            if(isset($result[0])) {
                $position = $result[0];
            }
        }

        return $position;
    }

    /**
     * This derives the attribute id from the attribute code.
     *
     * @param $attributeCode
     * @return bool
     */
    public function getAttributeIdFromCode($attributeCode)
    {
        $attributeId = false;
        if(Zend_Validate::is($attributeCode,'Digits')) {
            $attributeId = $attributeCode;
        } else {
            $listAttributeCodesToIds = $this->getConnection()->fetchPairs('SELECT attribute_code, attribute_id FROM eav_attribute');
            if(isset($listAttributeCodesToIds[$attributeCode])) {
                $attributeId = $listAttributeCodesToIds[$attributeCode];
            }
        }

        return $attributeId;
    }

    /**
     * This increments sort orders so that when we change the sort order of our master attribute, it won't be changed to a sort order value that already exists.
     *
     * @param $positionToIncrementFrom
     * @param $masterAttributeSetId
     */
    public function incrementAttributePositions($positionToIncrementFrom, $masterAttributeSetId)
    {
        $adapter = $this->getConnection();
        $table = $this->getTable('eav/entity_attribute');

        $where = array(
            $adapter->quoteIdentifier('sort_order') . ' >= ?' => $positionToIncrementFrom,
            $adapter->quoteIdentifier('attribute_set_id') . ' = ?' => $masterAttributeSetId,
        );

        $data  = array('sort_order' => new Zend_Db_Expr('sort_order + 1'));
        $adapter->update($table, $data, $where);
    }

    /**
     * Determines which sort orders need to be incremented.
     *
     * @param $positionOfAttribute
     * @param $insertBeforeOrAfter
     * @return mixed
     */
    public function getPositionToIncrementFrom($positionOfAttribute, $insertBeforeOrAfter)
    {
        if($insertBeforeOrAfter == self::ATTRIBUTE_POSITION_AFTER) {
            //If we are inserting after, we increment all sort_orders that come after the target attribute, if we add 1, then only those come after are incremented.
            $positionToIncrement = $positionOfAttribute + 1;
        } else {
            //if we are inserting before, we want to increment the sort_order of the target attribute as well as all other attributes (in that set) that come after it.
            $positionToIncrement = $positionOfAttribute;
        }

        return $positionToIncrement;
    }

    /**
     * This changes the sort order value, so that it comes either before (or after) the target attribute.
     *
     * @param $entityAttributeId
     * @param $positionToIncrementFrom
     */
    public function changeAttributeSortOrderValue($entityAttributeId, $newSortOrderValue)
    {
        //this takes the master attribute and changes the sort_order so that it comes before the target attribute.
        $this->updateTableRow('eav/entity_attribute',
            'entity_attribute_id', $entityAttributeId,
            'sort_order', $newSortOrderValue
        );
    }
}
