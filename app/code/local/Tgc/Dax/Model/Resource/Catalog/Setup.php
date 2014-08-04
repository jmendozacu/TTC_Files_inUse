<?php
/**
 * Dax adcode entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Dax_Model_Resource_Catalog_Setup extends Guidance_Setup_Model_Resource_Setup
{


    /**
     * Transferts values of EAV model and changes it's backend type
     *
     * @param string $from Source backend type
     * @param string $to Target backend type
     * @param int $attributId Attribute ID
     * @param string $entityTable Entity table
     * @param int $entityTypeId Entity type ID
     * @throws Exception On errors
     */
    public function transferAttributeValues($from, $to, $attributId, $entityTable, $entityTypeId)
    {
        $db = $this->getConnection();
        $db->beginTransaction();

        try {
            $select = $this->_getTransferSelect($from, $to, $attributId, $entityTable, $entityTypeId);
            $this->_insertToRightTable($to, $entityTable, $select);
            $this->_updateAttributeToRightType($to, $attributId);
            $this->_deleteFromWrongTable($from, $attributId, $entityTable, $entityTypeId);
            $db->commit();
            Mage::log(
                "Values for attribute #$attributId have been moved from $from to $to table;"
                . " backend type of attribute is $to."
            );
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    private function _getWrongBackendTypeAttributes($backendType, $frontendInputs)
    {
        return $this->getConnection()
            ->select()
            ->from(
                array('a' => $this->getTable('eav_attribute')),
                array(
                    'attribute_id',
                    'from' => 'backend_type',
                    'entity_type_id',
                )
            )
            ->join(array('t' => $this->getTable('eav_entity_type')), 'a.entity_type_id = t.entity_type_id', array('entity_table'))
            ->where('is_user_defined <> 0 OR is_user_defined IS NULL')
            ->where("frontend_input IN (?)", $frontendInputs)
            ->where('backend_type <> ?', $backendType)
            ->query();
    }

    private function _getTransferSelect($from, $to, $attributId, $entityTable, $entityTypeId)
    {
        return $this->getConnection()
            ->select()
            ->from(
                array('e' => $entityTable . '_' . $from),
                array(
                    'e.entity_type_id',
                    'e.attribute_id',
                    'e.store_id',
                    'e.entity_id',
                    'value' => $this->_getValueConverterExpression('e.value', $from, $to),
                )
            )
            ->where('entity_type_id = ?', $entityTypeId)
            ->where('attribute_id = ?', $attributId);
    }

    private function _getValueConverterExpression($column, $from, $to)
    {
        if ($from == 'varchar' && $to == 'int') {
            return new Zend_Db_Expr("IF($column IS NULL, $column, CAST($column AS UNSIGNED))");
        }

        return $column;
    }

    private function _insertToRightTable($to, $entityTable, Varien_Db_Select $transferSelect)
    {
        $insert = $this->getConnection()
            ->insertFromSelect(
                $transferSelect->forUpdate(true),
                $entityTable . '_' . $to,
                array('entity_type_id', 'attribute_id', 'store_id', 'entity_id', 'value'),
                Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE
            );

        $this->getConnection()->query($insert);
    }

    private function _deleteFromWrongTable($from, $attributId, $entityTable, $entityTypeId)
    {
        $db = $this->getConnection();
        $db->delete(
            $entityTable . '_' . $from,
            "entity_type_id = {$db->quote($entityTypeId)} AND attribute_id = {$db->quote($attributId)}"
        );
    }

    private function _updateAttributeToRightType($to, $attributId)
    {
        $db = $this->getConnection();
        $db->update(
            $this->getTable('eav_attribute'),
            array('backend_type' => $to),
            $db->quoteInto('attribute_id = ?', $attributId)
        );
    }
}