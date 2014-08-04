<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Lectures_Model_Resource_Lectures_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    const RESUME = 'r';

    protected function _construct()
    {
        $this->_init('lectures/lectures');
    }

    public function lectureNumberExistsForProduct($lectureNumber, $productId)
    {
        $lectureIdRequested = Mage::app()->getFrontController()->getAction()->getRequest()->getParam('lectureid');

        $recordsRepeatingLectureNumber = $this->addFieldToFilter('lecture_number', $lectureNumber)
            ->addFieldToFilter('product_id', $productId);

        if ($lectureIdRequested) {
            $recordsRepeatingLectureNumber->addFieldToFilter('id', array('neq' => $lectureIdRequested));
        }

        return $recordsRepeatingLectureNumber->count();
    }

    public function addProgressForUser($webUserId)
    {
        if (!isset($this->_joinedTables[self::RESUME])) {
            $this->_joinedTables[self::RESUME] = true;
            $this->getSelect()
                ->joinLeft(
                    array(self::RESUME => $this->getTable('tgc_dl/crossPlatformResume')),
                    $this->getConnection()->quoteInto('lecture_id=id AND web_user_id = ?', $webUserId),
                    'progress'
                );
        }

        return $this;
    }

    /**
     * Returns average duration of lectures collection
     *
     * @return integer Duration in seconds
     */
    public function getAverageDuration()
    {
        $duration = 0;
        $count = 0;

        foreach ($this as $lecture) {
            $lectDurat = $lecture->getAudioDuration() + $lecture->getVideoDuration();
            if ($lecture->getAudioDuration() && $lecture->getVideoDuration()) {
                $lectDurat /= 2;
            }
            $duration += $lectDurat;
            $count++;
        }

        return $count ? (int)($duration / $count) : 0;
    }

    /**
     * Adds product to filter
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Tgc_Lectures_Model_Resource_Lectures_Collection
     */
    public function addProductToFilter(Mage_Catalog_Model_Product $product)
    {
        return $this->addFieldToFilter('product_id', $product->getId());
    }
}
