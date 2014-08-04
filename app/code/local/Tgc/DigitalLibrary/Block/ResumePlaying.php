<?php
/**
 * Digital Library Left Nav
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_ResumePlaying extends Mage_Core_Block_Template
implements Mage_Widget_Block_Interface
{
    private $_lastStreamed;

    const COOKIE_NAME     = 'show_resume_block';
    const COOKIE_LIFETIME = 86400;
    const CACHE_TAG       = 'RESUME_BLOCK';

    public function shouldShowBlock()
    {
        return Mage::helper('tgc_cms')->isAuthenticated()
            && Mage::getSingleton('customer/session')->isLoggedIn()
            && !Mage::getModel('core/cookie')->get(self::COOKIE_NAME);
    }

    public function getCookieName()
    {
        return self::COOKIE_NAME;
    }

    public function getCookieLifetime()
    {
        return self::COOKIE_LIFETIME;
    }

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/resumePlaying.phtml');
        }

        $this->addData(array('cache_lifetime' => false));
        $this->addCacheTag(array(
            self::CACHE_TAG,
        ));
    }


    public function hasCourseForResume()
    {
        return (bool)$this->_getLastStreamed();
    }

    /**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $last = $this->_getLastStreamed();
        $info = array(
            'show_resume'    => Mage::getModel('core/cookie')->get(self::COOKIE_NAME) ? 1 : 0,
            'lecture_number' => $last ? $last->getLectureNumber() : 0,
            'customer' => Mage::getSingleton('customer/session')->getCustomerId(),
            'logged_in' => Mage::getsingleton('customer/session')->isLoggedIn(),
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            'template' => $this->getTemplate(),
            'user_type' => Mage::helper('tgc_cms')->getUserType(),
        );

        return array_merge(parent::getCacheKeyInfo(), $info);
    }

    public function getCourseForResume()
    {
        $data = array();
        $lastStreamed = $this->_getLastStreamed();
        if (!$lastStreamed) {
            return false;
        }

        $data['lecture_number'] = $lastStreamed->getLectureNumber();
        $data['title']          = $lastStreamed->getTitle();
        $data['format']         = $lastStreamed->getFormat();
        $data['description']    = $lastStreamed->getDescription();
        $data['num_lectures']   = $this->_getNumLectures($lastStreamed->getProductId());
        $data['course_url']     = $this->_getCourseUrl($lastStreamed->getProductId(), $lastStreamed->getFormat(), $lastStreamed->getLectureNumber());
        $data['image_url']      = (string)Mage::helper('catalog/image')->init(
                                              Mage::getModel('catalog/product')->load(
                                                  $lastStreamed->getProductId()
                                              ), 'image')->resize(80,60);

        return $data;
    }

    private function _getNumLectures($productId)
    {
        $lectures = Mage::getResourceModel('lectures/lectures_collection')
            ->addFieldToFilter('product_id', array('eq' => $productId))
            ->getColumnValues('entity_id');

        return count($lectures);
    }

    private function _getLastStreamed()
    {
        if (isset($this->_lastStreamed)) {
            return $this->_lastStreamed;
        }

        $collection = Mage::getResourceModel('tgc_dl/crossPlatformResume_collection')
            ->addFieldToFilter('web_user_id', array('eq' => Mage::getSingleton('customer/session')->getCustomer()->getWebUserId()))
            ->setOrder('stream_date', 'desc');
        $collection->getSelect()
            ->joinLeft(array('lecture' => 'lectures'),
                'main_table.lecture_id = lecture.id',
                array('product_id', 'lecture_number', 'title', 'description')
            )
            ->limit(1);

        if ($collection->getSize() < 1) {
            return false;
        }

        $this->_lastStreamed = $collection->getFirstItem();

        return $this->_lastStreamed;
    }

    protected function _getCourseUrl($productId, $format, $lecture)
    {
        return Mage::getUrl('digital-library/course/view', array('id' => $productId, 'format' => $format, 'resume' => $lecture));
    }

    public function getStartUrl()
    {
        $data = array();
        $data['course_url'] = Mage::getUrl('digital-library/account');

        return $data;
    }

    public function getWidgetUrl()
    {
        return Mage::getUrl('digital-library/account/closeResume');
    }
}
