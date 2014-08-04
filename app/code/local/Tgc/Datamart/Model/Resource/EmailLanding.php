<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_Resource_EmailLanding extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_datamart/emailLanding', 'entity_id');
    }

    /**
     * Returns the entity id for a given category and course
     *
     * @param string $category
     * @param int $courseId
     * @return int entity_id
     */
    public function getIdByCategoryAndCourse($category, $courseId)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('category = :category')
            ->where('course_id = :course_id')
            ->where('landing_page_type = :landing_page_type');

        $bind = array(
            ':category'  => (string)$category,
            ':course_id' => (int)$courseId,
            ':landing_page_type' => (int)Tgc_Datamart_Model_Source_LandingPage_Type::TYPE_EMAIL,
        );

        return $adapter->fetchOne($select, $bind);
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
     * Add the sort order as position in product collection
     *
     * @param $collection Mage_Catalog_Model_Resource_Product_Collection
     * @param string $category
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function addSortOrderToCollection($collection, $category)
    {
        $collection->getSelect()
            ->joinLeft(
            array('tdel' => $collection->getTable('tgc_datamart/emailLanding')),
            'tdel.course_id = `at_course_id`.`value` AND tdel.category = \'' . $category . '\'',
            array('landing_position' => 'tdel.sort_order')
        );

        return $collection;
    }

    /**
     * Get an array of course ids given category
     *
     * @param string $category
     * @return array of ids
     */
    public function getCourseIdsByCategory($category, $type)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable(), 'course_id')
            ->where('category = :category')
            ->where('date_expires >= :date')
            ->where('landing_page_type = :landing_page_type');

        $bind = array(
            ':category'  => (string)$category,
            ':date' => Mage::getModel('core/date')->gmtDate(),
            ':landing_page_type' => (int)$type,
        );

        return $adapter->fetchCol($select, $bind);
    }
}
