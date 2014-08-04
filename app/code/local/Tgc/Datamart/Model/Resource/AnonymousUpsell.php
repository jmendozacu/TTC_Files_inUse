<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Resource_AnonymousUpsell extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_datamart/anonymousUpsell', 'entity_id');
    }

    /**
     * Delete rows given an array of entity ids
     *
     * @param array $idsToDelete
     */
    public function deleteRowsByIds(array $idsToDelete)
    {
        $adapter = $this->_getWriteAdapter();

        $adapter->delete(
            $this->getMainTable(),
            array($adapter->quoteInto("`entity_id` IN(?) ", $idsToDelete))
        );
    }

    /**
     * Returns the entity id for a given subject and course
     *
     * @param int $subjectId
     * @param int $courseId
     * @return int entity_id
     */
    public function getIdBySubjectAndCourse($subjectId, $courseId)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('subject_id = :subject_id')
            ->where('course_id = :course_id');

        $bind = array(
            ':subject_id'  => (int)$subjectId,
            ':course_id' => (int)$courseId,
        );

        return $adapter->fetchOne($select, $bind);
    }

    /**
     * Get array of course id's by subject id
     *
     * @param array $subjectIds
     * @return array of course_id's
     */
    public function getCourseIdsBySubjectIds($subjectIds)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable(), 'course_id')
            ->where('subject_id IN(?)', $subjectIds);

        return $adapter->fetchAll($select);
    }

    /**
     * Add the sort order as position in product collection
     *
     * @param $collection Mage_Catalog_Model_Resource_Product_Collection
     * @param array $subjectIds
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function addSortOrderToCollection($collection, $subjectIds)
    {
        $collection->joinTable(
            array('tdau' => 'tgc_datamart/anonymousUpsell'),
            'course_id = course_id ',
            array('position' => 'sort_order'),
            'subject_id IN (' . join(',', $subjectIds) . ')'
        );

        return $collection;
    }

    public function getProductIdsInCart($quoteId)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from('sales_flat_quote_item', 'product_id')
            ->where('quote_id = :quoteId')
            ->where('parent_item_id IS NULL')
            ->order('created_at');

        $bind = array(
            ':quoteId' => (int)$quoteId,
        );

        return (array)$adapter->fetchCol($select, $bind);
    }

    public function getSubjectIdsFromProductIds(array $productIds)
    {
        $subjectIds = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter('entity_id', array('in' => $productIds))
            ->addAttributetoSelect('primary_subject')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->getColumnValues('primary_subject');

        return $subjectIds;
    }
}
