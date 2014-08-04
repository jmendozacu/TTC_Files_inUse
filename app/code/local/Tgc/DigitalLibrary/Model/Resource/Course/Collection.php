<?php
/**
 * Collection of courses
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Resource_Course_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    const ACCESS   = 'a';
    const LECTURES = 'l';
    const RESUME   = 'r';

    private $_joined = array();

    /**
     * Joins acccess right table and groups by entity_id
     * Columns:
     *    - web_user_id;
     *    - date_purchased;
     *    - is_downloadable;
     *    - digital_transcript_purchased;
     *    - format.
     *
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection Self
     */
    public function addAccessRights()
    {
        if (!isset($this->_joined[self::ACCESS])) {
            $this->joinTable(
                array(self::ACCESS => 'tgc_dl/accessRights'),
                'course_id=entity_id',
                array(
                    'web_user_id'     => 'web_user_id',
                    'date_purchased'  => 'date_purchased',
                    'is_downloadable' => 'is_downloadable',
                    'digital_transcript_purchased' => 'digital_transcript_purchased',
                    'format' => 'format'
                )
            );
            $this->groupByAttribute('entity_id');
            $this->_joined[self::ACCESS] = true;
        }

        return $this;
    }

    /**
     * Adds filter by web user ID
     * Joins access right table if it's not joined yet
     *
     * @param string $id
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection Self
     */
    public function addFilterByWebUserId($id)
    {
        $this->addAccessRights()
            ->getSelect()
            ->where(self::ACCESS . '.web_user_id = ?', $id);


         return $this;
    }

    /**
     * Adds filter by web user ID from customer
     *
     * @param Mage_Customer_Model_Customer $customer Customer model
     * @throws InvalidArgumentException If customer is not loaded
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection Self
     */
    public function addFilterByCustomer(Mage_Customer_Model_Customer $customer)
    {
        if ($customer->isObjectNew()) {
            throw new InvalidArgumentException('Undefined customer.');
        }

        return $this->addFilterByWebUserId($customer->getWebUserId());
    }

    /**
     * Adds lectures summary info
     * Columns:
     *    - lectures_number;
     *    - audio_duration;
     *    - video_duration.
     *
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection
     */
    public function addLecturesInfo()
    {
        if (!isset($this->_joined[self::LECTURES])) {
            $this->joinTable(
                array(self::LECTURES => 'lectures/lectures'),
                'product_id=entity_id',
                array(
                    'lectures_number' => new Zend_Db_Expr('COUNT(id)'),
                    'audio_duration'  => new Zend_Db_Expr('SUM(audio_duration)'),
                    'video_duration'  => new Zend_Db_Expr('SUM(video_duration)'),
                ),
                null,
                'left'
            );
            $this->groupByAttribute('entity_id');
            $this->_joined[self::LECTURES] = true;
        }

        return $this;
    }

    /**
     * Adds info on progress
     * Columns:
     *    - total_progress Total progress of course;
     *    - stream_date Last stream date.
     *
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection
     */
    public function addProgress()
    {
        if (!$this->_joined[self::RESUME]) {
            $this->addLecturesNumber()
                ->addAccessRights()
                ->joinTable(
                    array(self::RESUME => 'tgc_dl/crossPlatformResume'),
                    'lecture_id=' . self::LECTURES . '.id',
                    array(
                        'total_progress' => new Zend_Db_Expr('SUM(progress)'),
                        'stream_date' => new Zend_Db_Expr('MAX(stream_date)')
                    ),
                    self::RESUME . '.web_user_id = ' . self::ACCESS . '.web_user_id',
                    'left'
                );
            $this->groupByAttribute('entity_id');
            $this->_joined[self::RESUME];
        }

        return $this;
    }


    /**
     * Filters free prospect lectures
     *
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection Self
     */
    public function addFreeProspectFilter()
    {
        $now = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $this->addAttributeToFilter(
            'monthly_free_lecture_from',
            array('datetime' => true, 'to' => $now)
        );
        $this->addAttributeToFilter(
            'monthly_free_lecture_to',
            array('or' => array(
                array('datetime' => true, 'from' => $now),
                array('is' => new Zend_Db_Expr('null'))
                ),
            ),
            'left'
        );

        return $this;
    }

    /**
     * Filters free marketing lectures
     *
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection Self
     */
    public function addFreeMarketingFilter()
    {
        $now = $this->getConnection()->quote(
            Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT)
        );
        $this->_addAttributeJoin('marketing_free_lecture_from', 'left');
        $this->_addAttributeJoin('marketing_free_lecture_to', 'left');
        $fromVal = $this->_getAttributeFieldName('marketing_free_lecture_from');
        $toVal = $this->_getAttributeFieldName('marketing_free_lecture_to');

        $this->getSelect()->where(implode(' OR ', array(
            "($fromVal < $now AND $toVal IS NULL)",
            "($fromVal IS NULL AND $now < $toVal)",
            "($fromVal < $now AND $now < $toVal)"
        )));

        return $this;
    }

    /**
     * Adds filter by set
     *
     * @param Mage_Catalog_Model_Product $product Product with Sets attribute set
     * @throws InvalidArgumentException On invalid set
     * @return  Tgc_DigitalLibrary_Model_Resource_Course_Collection
     */
    public function addFilterBySet(Mage_Catalog_Model_Product $set)
    {
        if (!$this->_factory->getHelper('tgc_catalog')->isSetProduct($set)) {
            throw new InvalidArgumentException('Invalid set; attribute set of product should be Sets.');
        }

        $ids = array_filter(explode(',', $set->getSetMembers()));
        $ids = array_map('trim', $ids);

        if (empty($ids)) {
            $ids[] = -1; // to return empty collection if set does not have products
        }

        return $this->addFieldToFilter('course_id', array('in' => $ids));
    }

    protected function _initSelect()
    {
        parent::_initSelect();

        $this->addFieldToFilter('type_id', Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE);
    }

    protected function _init($model, $entityModel = 'catalog/product')
    {
        parent::_init('tgc_dl/course', $entityModel);
    }
}
