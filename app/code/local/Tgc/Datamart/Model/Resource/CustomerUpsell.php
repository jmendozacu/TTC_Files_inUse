<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Resource_CustomerUpsell extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_datamart/customerUpsell', 'entity_id');
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
     * Returns the entity id for a given segment and course
     *
     * @param string $segment
     * @param int $courseId
     * @return int entity_id
     */
    public function getIdBySegmentAndCourse($segment, $courseId)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('segment_group = :segment')
            ->where('course_id = :course_id');

        $bind = array(
            ':segment'  => (string)$segment,
            ':course_id' => (int)$courseId,
        );

        return $adapter->fetchOne($select, $bind);
    }

    /**
     * Get array of course id's by customer segment
     *
     * @param string $customerSegment
     * @return array of course_id's
     */
    public function getCourseIdsByCustomerSegment($customerSegment)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'course_id')
            ->where('segment_group = :segment');
        $bind = array(
            ':segment' => (string)$customerSegment,
        );

        return (array)$adapter->fetchCol($select, $bind);
    }

    /**
     * Add the sort order as position in product collection
     *
     * @param $collection Mage_Catalog_Model_Resource_Product_Collection
     * @param string $customerSegment
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function addSortOrderToCollection($collection, $customerSegment)
    {
        $collection->joinTable(
            array('tdcu' => 'tgc_datamart/customerUpsell'),
            'course_id = course_id',
            array('position' => 'sort_order'),
            'segment_group = \'' . $customerSegment . '\''
        );

        return $collection;
    }
}
