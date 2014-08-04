<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Resource_CrossPlatformResume extends Mage_Core_Model_Resource_Db_Abstract
{
    const COOKIE_NAME = 'resume_lecture';

    const FORMAT_AUDIO = 0;
    const FORMAT_VIDEO = 1;

    protected function _construct()
    {
        $this->_init('tgc_dl/crossPlatformResume', 'entity_id');
    }

    public function getLectureData($courseId, $webUserId = null, $format)
    {
        $durationFrom = $format ? 'video_duration' : 'audio_duration';

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from(array('lectures' => 'lectures'),
                array('*', 'duration' => $durationFrom));
        if (!empty($webUserId)) {
            $select->joinLeft(
                array('cpr' => $this->getMainTable()),
                '(cpr.lecture_id = lectures.id AND cpr.web_user_id = \'' . $webUserId . '\')',
                array('stream_date', 'progress', 'download_date', 'watched')
            )->joinLeft(
                array('access' => $this->getTable('tgc_dl/accessRights')),
                '(access.course_id = lectures.product_id AND access.web_user_id = \'' . $webUserId . '\')',
                array('is_downloadable')
            );
        } else {
            return array();
        }
        $select->where('lectures.product_id = :courseId')
            ->group('lectures.id')
            ->order('lecture_number asc');
        $bind = array(
            ':courseId' => (int)$courseId,
        );

        return (array)$adapter->fetchAll($select, $bind);
    }

    public function saveProgressForCustomer($webUserId, $lectureId, $progress, $format)
    {
        if (empty($webUserId)) {
            return;
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where('web_user_id = :webUserId')
            ->where('lecture_id = :lectureId')
            ->where('format = :format');
        $bind = array(
            ':webUserId' => (string)$webUserId,
            ':lectureId' => (int)$lectureId,
            ':format'    => (int)$format,
        );
        $entityId = $adapter->fetchOne($select, $bind);

        if (!empty($entityId)) {
            $this->_updateProgress($entityId, $progress);
        } else {
            $this->_saveProgress($webUserId, $lectureId, $progress, $format);
        }
    }

    public function setWatchedForCustomer($webUserId, $lectureId, $format)
    {
        if (empty($webUserId)) {
            return;
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where('web_user_id = :webUserId')
            ->where('lecture_id = :lectureId')
            ->where('format = :format');
        $bind = array(
            ':webUserId' => (string)$webUserId,
            ':lectureId' => (int)$lectureId,
            ':format'    => (int)$format,
        );
        $entityId = $adapter->fetchOne($select, $bind);

        if (!empty($entityId)) {
            $this->_setWatched($entityId);
        }
    }

    public function saveDownloadDateForCustomer($webUserId = null, $lectureId, $format)
    {
        if (empty($webUserId)) {
            return;
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where('web_user_id = :webUserId')
            ->where('lecture_id = :lectureId')
            ->where('format = :format');
        $bind = array(
            ':webUserId' => (string)$webUserId,
            ':lectureId' => (int)$lectureId,
            ':format'    => (int)$format,
        );
        $entityId = $adapter->fetchOne($select, $bind);

        if (!empty($entityId)) {
            $this->_updateDownloadDate($entityId);
        } else {
            $this->_saveDownloadDate($webUserId, $lectureId, $format);
        }
    }

    private function _saveDownloadDate($webUserId = null, $lectureId, $format)
    {
        if (empty($webUserId)) {
            return;
        }

        $adapter = $this->_getWriteAdapter();
        $now  = date(
            Varien_Date::DATETIME_PHP_FORMAT,
            Mage::getModel('core/date')->timestamp(time())
        );
        $bind = array(
            'entity_id'     => null,
            'lecture_id'    => (int)$lectureId,
            'web_user_id'   => (string)$webUserId,
            'progress'      => 0,
            'download_date' => $now,
            'stream_date'   => null,
            'format'        => $format,
            'watched'       => 0,
        );
        try {
            $adapter->insert($this->getMainTable(), $bind);
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    private function _updateDownloadDate($entityId)
    {
        $adapter = $this->_getWriteAdapter();
        $now  = date(
            Varien_Date::DATETIME_PHP_FORMAT,
            Mage::getModel('core/date')->timestamp(time())
        );
        $bind = array(
            'download_date' => $now,
        );
        try {
            $adapter->update(
                $this->getMainTable(),
                $bind,
                array(
                    $adapter->quoteInto("`entity_id` =(?) ", $entityId),
                )
            );
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    private function _setWatched($entityId)
    {
        $adapter = $this->_getWriteAdapter();
        $bind = array(
            'watched'    => '1',
        );
        $adapter->update(
            $this->getMainTable(),
            $bind,
            array(
                $adapter->quoteInto("`entity_id` =(?) ", $entityId),
            )
        );
    }

    private function _saveProgress($webUserId = null, $lectureId, $progress, $format)
    {
        if (empty($webUserId)) {
            return;
        }

        $adapter = $this->_getWriteAdapter();
        $bind = array(
            'entity_id'     => null,
            'lecture_id'    => (int)$lectureId,
            'web_user_id'   => (string)$webUserId,
            'progress'      => (int)$progress,
            'download_date' => null,
            'stream_date'   => now(),
            'format'        => (int)$format,
        );
        try {
            $adapter->insert($this->getMainTable(), $bind);
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    private function _updateProgress($entityId, $progress)
    {
        $adapter = $this->_getWriteAdapter();
        $bind = array(
            'progress'    => (int)$progress,
            'stream_date' => now(),
        );
        try {
            $adapter->update(
                $this->getMainTable(),
                $bind,
                array(
                    $adapter->quoteInto("`entity_id` =(?) ", $entityId),
                )
            );
        } catch (Exception $e) {
            Mage::logException($e);
        }
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

    public function getCustomerIdByWebUserId($webUserId = null)
    {
        if (empty($webUserId)) {
            return 0;
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('customer/entity'), 'entity_id')
            ->where('web_user_id = :webUserId');
        $bind = array(
            ':webUserId' => (string)$webUserId,
        );

        return (int)$adapter->fetchOne($select, $bind);
    }

    public function getWebUserIdByDaxCustomerId($daxCustomerId = null)
    {
        if (empty($daxCustomerId)) {
            return false;
        }

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getTable('customer/entity'), 'web_user_id')
            ->where('dax_customer_id = :daxCustomerId');
        $bind = array(
            ':daxCustomerId' => (string)$daxCustomerId,
        );

        return $adapter->fetchOne($select, $bind);
    }

    public function checkIfLectureExistsById($lectureId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from('lectures', 'id')
            ->where('id = :lectureId');
        $bind = array(
            ':lectureId' => (int)$lectureId,
        );

        return (bool)$adapter->fetchOne($select, $bind);
    }
}
