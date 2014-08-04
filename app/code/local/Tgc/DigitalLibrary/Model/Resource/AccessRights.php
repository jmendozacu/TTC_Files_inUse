<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Resource_AccessRights extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('tgc_dl/accessRights', 'entity_id');
    }

    /**
     * Get a collection of all courses available for a customer
     *
     * @param $customer
     * @param int $format
     * @return $collection
     */
    public function getCoursesCollectionForCustomer(Mage_Customer_Model_Customer $customer, $format = null)
    {
        $courseIds = (array)$this->_getPurchasedCourseIdsForCustomer($customer, $format);
        $webUserIds = $this->_getMergedWebUserIds($customer);

        if ($customer->getFreeLectureProspect() === '1') {
            $freeLectureIds = (array)$this->_getFreeLectureProspectLectureIds();
            $courseIds = array_merge($courseIds, $freeLectureIds);
        }

        if ($customer->getIsProspect() === '0') {
            $monthlyFreeLectureIds = (array)$this->_getMonthlyFreeLectureIds();
            $courseIds = array_merge($courseIds, $monthlyFreeLectureIds);
        }

        $formatFilter = array(Tgc_DigitalLibrary_Model_Source_Streaming::BOTH);
        if (isset($format)) {
            $formatFilter[] = $format ? Tgc_DigitalLibrary_Model_Source_Streaming::AUDIO_ONLY
                : Tgc_DigitalLibrary_Model_Source_Streaming::VIDEO_ONLY;
        } else {
            $formatFilter[] = Tgc_DigitalLibrary_Model_Source_Streaming::AUDIO_ONLY;
            $formatFilter[] = Tgc_DigitalLibrary_Model_Source_Streaming::VIDEO_ONLY;
        }

        $collection = Mage::getResourceModel('catalog/product_collection')
            ->useOnlyIterativeKeys()
            ->addAttributeToFilter('entity_id', array('in' => $courseIds))
            ->addAttributeToFilter('type_id', array('eq' => 'configurable'))
            ->addAttributeToSelect('professor')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('thumbnail')
            ->addAttributeToSelect('course_id');

        $adapter = $collection->getSelect()->getAdapter();

        $collection->joinTable(
            array('lectures' => $this->getTable('lectures/lectures')),
            'product_id = entity_id',
            array(
                'lect_id' => 'id', 'num_lectures' => new Zend_Db_Expr('COUNT(id)'),
                'media_format' => new Zend_Db_Expr('IF(access.format IS NULL, IF(SUM(lectures.audio_duration) IS NULL, 1, 0), access.format)'),
                'buy_date' => new Zend_Db_Expr('IF(access.date_purchased IS NULL, NOW(), access.date_purchased)'),
                'format_text' => new Zend_Db_Expr('IF(access.format IS NULL, IF(SUM(lectures.audio_duration) IS NULL, \'Video\', \'Audio\'), IF(access.format = 0, \'Audio\', \'Video\'))'),
            ),
            null,
            'inner'
        )->joinTable(
            array('access' => $this->getMainTable()),
            'course_id = entity_id',
            array('format', 'date_purchased', 'digital_transcript_purchased', 'is_downloadable',),
            sprintf('web_user_id IN (%s)', join(',', array_map(array($adapter, 'quote'), $webUserIds))),
            'left'
        )->joinTable(
            array('cpr' => $this->getTable('tgc_dl/crossPlatformResume')),
            'lecture_id = lect_id',
            array('total_progress' => new Zend_Db_Expr('SUM(progress)'), 'stream_date' => new Zend_Db_Expr('MAX(stream_date)')),
            'cpr.web_user_id = \'' . $customer->getWebUserId() . '\' AND (cpr.format = access.format OR access.format IS NULL)',
            'left'
        );
        $collection->getSelect()
            ->columns(array('total_duration' => new Zend_Db_Expr('IF(access.format = 0, IF(SUM(lectures.audio_duration) IS NULL, 0, SUM(lectures.audio_duration)), IF(SUM(lectures.video_duration) IS NULL, 0, SUM(lectures.video_duration)))')))
            ->columns(array('is_free' => new Zend_Db_Expr('IF(access.format IS NULL, 1, 0)')))
            ->group(array('e.entity_id','access.format'));

        if (!is_null($format)) {
            $type = $format ? 'video' : 'audio';
            $where = 'access.format = ? OR access.format IS NULL AND lectures.' . $type . '_duration IS NOT NULL';
            $collection->getSelect()->where($where, $format);
        }

        $this->joinAttributeToSelect($collection->getSelect(), 'availability_of_streaming');
        $collection->getSelect()->where('availability_of_streaming.value IS NULL OR '
            . sprintf('availability_of_streaming.value IN (%s)', join(',', array_map(array($adapter, 'quote'), $formatFilter)))
            . ' OR access.is_downloadable = "1"');

        return $collection;
    }

    protected function joinAttributeToSelect($select, $attrCode)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attrCode);
        $attrId = $attribute->getAttributeId();
        $select->joinLeft(
            array($attrCode => $attribute->getBackendTable()),
            '(' . $attrCode . '.entity_id = e.entity_id) AND (' . $attrId . ' = ' . $attrCode . '.attribute_id)',
            array($attrCode => $attrCode . '.value')
        );

        return $select;
    }

    /**
     * Get all product ids purchased by customer
     * These allow streaming
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param format
     * @return array
     */
    private function _getPurchasedCourseIdsForCustomer(Mage_Customer_Model_Customer $customer, $format)
    {
        $webUserIds = $this->_getMergedWebUserIds($customer);
        if (empty($webUserIds)) {
            return array();
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'course_id')
            ->where($adapter->quoteInto("web_user_id IN(?) ", $webUserIds));
        if (!is_null($format)) {
            $select->where($adapter->quoteInto("format =(?) ", $format));
        }

        return (array)$adapter->fetchCol($select);
    }

    private function _getMergedWebUserIds($customer)
    {
        $daxCustomerId = $customer->getDaxCustomerId();
        if (empty($daxCustomerId)) {
            return array($customer->getWebUserId());
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('tgc_dl/mergeAccounts'), 'mergeto_dax_customer_id')
            ->where('dax_customer_id = :daxCustomerId');
        $bind = array(
            ':daxCustomerId' => (string)$daxCustomerId,
        );
        $merges = (array)$adapter->fetchCol($select, $bind);
        $select = $adapter->select()
            ->from($this->getTable('tgc_dl/mergeAccounts'), 'dax_customer_id')
            ->where('mergeto_dax_customer_id = :daxCustomerId');
        $bind = array(
            ':daxCustomerId' => (string)$daxCustomerId,
        );
        $mergeTos = (array)$adapter->fetchCol($select, $bind);

        $daxIds = array_filter(array_unique(array_merge($merges, $mergeTos, (array)$daxCustomerId)));
        if (empty($daxIds)) {
            return false;
        }

        $select = $adapter->select()
            ->from($this->getTable('customer/entity'), 'web_user_id')
            ->where($adapter->quoteInto("dax_customer_id IN(?) ", $daxIds));

        return (array)$adapter->fetchCol($select);
    }

    private function _getMergedDaxCustomerIds($customer)
    {
        $daxCustomerId = $customer->getDaxCustomerId();
        if (empty($daxCustomerId)) {
            return false;
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('tgc_dl/mergeAccounts'), 'mergeto_dax_customer_id')
            ->where('dax_customer_id = :daxCustomerId');
        $bind = array(
            ':daxCustomerId' => (string)$daxCustomerId,
        );
        $merges = (array)$adapter->fetchCol($select, $bind);
        $select = $adapter->select()
            ->from($this->getTable('tgc_dl/mergeAccounts'), 'dax_customer_id')
            ->where('mergeto_dax_customer_id = :daxCustomerId');
        $bind = array(
            ':daxCustomerId' => (string)$daxCustomerId,
        );
        $mergeTos = (array)$adapter->fetchCol($select, $bind);

        $daxIds = array_filter(array_unique(array_merge($merges, $mergeTos, (array)$daxCustomerId)));
        if (empty($daxIds)) {
            return false;
        }

        return $daxIds;
    }

    /**
     * Get all free for prospect product ids
     *
     * @return array
     */
    private function _getMonthlyFreeLectureIds()
    {
        $now = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $freeLectureIds1 = (array)Mage::getResourceModel('catalog/product_collection')
            ->setStore(Mage::app()->getStore())
            ->addAttributeToFilter('monthly_free_lecture_from', array(
                'datetime' => true,
                'to'       => $now,
            ))
            ->addAttributeToFilter('monthly_free_lecture_to', array('or' => array(
                0 => array('datetime' => true, 'from' => $now),
                1 => array('is' => new Zend_Db_Expr('null'))),
            ), 'left')
            ->getColumnValues('entity_id');

        $freeLectureIds2 = (array)Mage::getResourceModel('catalog/product_collection')
            ->setStore(Mage::app()->getStore())
            ->addAttributeToFilter('monthly_free_lecture_to', array(
                'datetime' => true,
                'from'       => $now,
            ))
            ->addAttributeToFilter('monthly_free_lecture_from', array('is' => new Zend_Db_Expr('null')))
            ->getColumnValues('entity_id');

        $allFreelectureIds = array_merge($freeLectureIds1, $freeLectureIds2);

        return $allFreelectureIds;
    }

    /**
     * Get all free marketing lecture product ids
     *
     * @return array
     */
    private function _getFreeLectureProspectLectureIds()
    {
        $now = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $freeLectureIds1 = (array)Mage::getResourceModel('catalog/product_collection')
            ->setStore(Mage::app()->getStore())
            ->addAttributeToFilter('marketing_free_lecture_from', array(
                'datetime' => true,
                'to'       => $now,
                ))
            ->addAttributeToFilter('marketing_free_lecture_to', array('or' => array(
                    0 => array('datetime' => true, 'from' => $now),
                    1 => array('is' => new Zend_Db_Expr('null'))),
                ), 'left')
            ->getColumnValues('entity_id');

        $freeLectureIds2 = (array)Mage::getResourceModel('catalog/product_collection')
            ->setStore(Mage::app()->getStore())
            ->addAttributeToFilter('marketing_free_lecture_to', array(
                'datetime' => true,
                'from'       => $now,
            ))
            ->addAttributeToFilter('marketing_free_lecture_from', array('is' => new Zend_Db_Expr('null')))
            ->getColumnValues('entity_id');

        $allFreelectureIds = array_merge($freeLectureIds1, $freeLectureIds2);

        return $allFreelectureIds;
    }

    public function getProductIdsFromCollection($collection)
    {
        return (array)$collection->getAllIds();
    }

    public function getCategoryIdsFromProductIds($productIds)
    {
        $adapter = $this->_getReadAdapter();
        $visibilities = array(
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
        );
        $select = $adapter->select()
            ->from('catalog_category_product_index', 'category_id')
            ->where($adapter->quoteInto("product_id IN(?) ", $productIds))
            ->where('store_id = :storeId')
            ->where($adapter->quoteInto("visibility IN(?) ", $visibilities))
            ->where('category_id <> :rootCategoryId');
        $bind = array(
            ':storeId' => (int)Mage::app()->getStore()->getId(),
            ':rootCategoryId' => (int)Mage::app()->getStore()->getRootCategoryId(),
        );

        $categoryIds = (array)$adapter->fetchCol($select, $bind);

        return array_unique(array_filter($categoryIds));
    }

    public function getCategorySelectOptions(array $categoryIds)
    {
        if (empty($categoryIds)) {
            return array();
        }

        $categories = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToFilter('entity_id', array('in' => $categoryIds))
            ->addAttributeToSelect('name');

        $selectOptions = array('all' => 'Select Category');
        foreach ($categories as $cat) {
            $selectOptions[$cat->getId()] = $cat->getName();
        }

        return $selectOptions;
    }

    public function checkCustomerAccessToCourse($webUserId, $courseId, $format)
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        $freeLectureIds = array();
        if ($customer->getFreeLectureProspect() === '1') {
            $freeLectureIds = (array)$this->_getFreeLectureProspectLectureIds();
        }
        $monthlyFreeLectureIds = array();
        if ($customer->getIsProspect() === '0') {
            $monthlyFreeLectureIds = (array)$this->_getMonthlyFreeLectureIds();
        }
        $freeIds = array_merge($monthlyFreeLectureIds, $freeLectureIds);
        if (in_array($courseId, $freeIds)) {
            return true;
        }

        $coursesPurchased = (array)$this->_getPurchasedCourseIdsForCustomer($customer, $format);

        return in_array($courseId, $coursesPurchased);
    }

    public function checkCustomerCanDownloadLecture($webUserId = null, $courseId, $format)
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();

        $webUserIds = $this->_getMergedWebUserIds($customer);

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'is_downloadable')
            ->where($adapter->quoteInto("web_user_id IN(?) ", $webUserIds))
            ->where('course_id = :courseId')
            ->where('format = :format');
        $bind = array(
            ':courseId'  => (int)$courseId,
            ':format'    => (int)$format,
        );

        return (bool)$adapter->fetchOne($select, $bind);
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
     * Get an array of all purchased products from access table and from history
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return array
     */
    public function getPurchasedProductsForCustomer(Mage_Customer_Model_Customer $customer)
    {
        if (!$customer->getId()) {
            return array();
        }

        $webUserIds = $this->_getMergedWebUserIds($customer);
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'course_id')
            ->where($adapter->quoteInto("web_user_id IN(?) ", $webUserIds))
            ->group('course_id');
        $access = (array)$adapter->fetchCol($select);

        $daxCustomerIds = $this->_getMergedDaxCustomerIds($customer);
        $select = $adapter->select()
            ->from($this->getTable('tgc_dl/purchaseHistory'), 'product_id')
            ->where($adapter->quoteInto("dax_customer_id IN(?) ", $daxCustomerIds));
        $history = (array)$adapter->fetchCol($select);

        return array_filter(array_merge($access, $history));
    }

    public function getAccessRightEntityId(array $data)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('course_id = :courseId')
            ->where('web_user_id = :webUserId')
            ->where('format = :format');
        $bind = array(
            ':courseId'  => (int)$data['course_id'],
            ':webUserId' => (string)$data['web_user_id'],
            ':format'    => (int)$data['format'],
        );

        return $adapter->fetchOne($select, $bind);
    }

    public function addAccessRight($accessRight)
    {
        $adapter = $this->_getWriteAdapter();
        $adapter->insertOnDuplicate(
            $this->getMainTable(),
            $accessRight
        );
    }

    public function addDigitalTranscriptAccessRight($courseId, $webUserId)
    {
        $productId = $this->_getProductIdFromSku($courseId);

        $adapter = $this->_getWriteAdapter();
        $bind = array(
            'digital_transcript_purchased' => 1,
        );
        try {
            $adapter->update(
                $this->getMainTable(),
                $bind,
                array(
                    $adapter->quoteInto("`course_id` = ? ", (int)$productId),
                    $adapter->quoteInto("`web_user_id` = ? ", (string)$webUserId),
                )
            );
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    protected function _getProductIdFromSku($sku)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from('catalog_product_entity', 'entity_id')
            ->where('sku = :sku');
        $bind = array(
            ':sku' => (string)$sku,
        );

        return $adapter->fetchOne($select, $bind);
    }

    public function getFormatByCustomerAndProduct($customer, $product)
    {
        $webUserIds = $this->_getMergedWebUserIds($customer);

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'format')
            ->where('course_id = :courseId')
            ->where($adapter->quoteInto("web_user_id IN(?) ", $webUserIds));
        $bind = array(
            ':courseId'  => (int)$product->getId(),
        );

        return $adapter->fetchOne($select, $bind);
    }

    /**
     * Check if user has rights to download
     *
     * @param $userId the web user id
     * @param $courseId the course id
     * @param $orderId the order increment id
     * @return bool
     */
    public function hasDownloadRights($userId, $courseId, $orderId)
    {
        $productId = $this->_getProductIdFromCourseId($courseId);

        if (empty($orderId) || empty($productId) || empty($userId)) {
            return false;
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('order_id = :orderId')
            ->where('course_id = :courseId')
            ->where('web_user_id = :userId');
        $bind = array(
            ':orderId'  => (int)$orderId,
            ':courseId' => (int)$productId,
            ':userId'   => (string)$userId,
        );
        $hasDownloadRights = (bool)$adapter->fetchOne($select, $bind);

        return $hasDownloadRights;
    }

    public function getAcess($webUserId, $lectureId, $format)
    {
        $courseId = $this->_getProductIdFromLectureId($lectureId);

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('web_user_id = :webUserId')
            ->where('format = :format')
            ->where('course_id = :courseId');
        $bind = array(
            ':webUserId' => (string)$webUserId,
            ':format'    => (int)$format,
            ':courseId'  => (int)$courseId,
        );

        $accessId = $adapter->fetchOne($select, $bind);

        if (empty($accessId)) {
            return false;
        }

        return Mage::getModel('tgc_dl/accessRights')->load($accessId);
    }

    public function getAkamaiIdFromLectureId($lectureId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from('lectures', 'akamai_download_id')
            ->where('id = :lectureId');
        $bind = array(
            ':lectureId' => (int)$lectureId,
        );
        $akamaiId = $adapter->fetchOne($select, $bind);

        return $akamaiId;
    }

    public function _getProductIdFromLectureId($lectureId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from('lectures', 'product_id')
            ->where('id = :lectureId');
        $bind = array(
            ':lectureId' => (int)$lectureId,
        );
        $productId = $adapter->fetchOne($select, $bind);

        return $productId;
    }

    public function _getProductIdFromCourseId($courseId)
    {
        $product = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter('course_id', array('eq' => $courseId))
            ->addAttributeToFilter('type_id', array('eq' => 'configurable'))
            ->getFirstItem();

        if ($product && $product->getId()) {
            return $product->getId();
        }

        return false;
    }
}
