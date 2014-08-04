<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Adminhtml_Catalog_Product_Edit_Tab_Lectures_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('lectures_grid');
        $this->setDefaultSort('lecture_number');
        $this->setDefaultDir('asc');
        $this->setUseAjax(true);
    }

    /**
     * Retirve currently edited product model
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _getProductId()
    {
        return Mage::registry('current_product')->getId();
    }


    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('lectures/lectures')->getCollection();
        $collection->addFieldToFilter('product_id', $this->_getProductId());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('audio_brightcove_id', array(
            'header'    => Mage::helper('lectures')->__('Audio Brightcove Id'),
            'index'     => 'audio_brightcove_id',
        ));

        $this->addColumn('video_brightcove_id', array(
            'header'    => Mage::helper('lectures')->__('Video Brightcove Id'),
            'index'     => 'video_brightcove_id',
        ));

        $this->addColumn('akamai_download_id', array(
            'header'    => Mage::helper('lectures')->__('Akamai Download Id'),
            'index'     => 'akamai_download_id',
        ));

        $this->addColumn('lecture_number', array(
            'header'    => Mage::helper('lectures')->__('Lecture Number'),
            'index'     => 'lecture_number',
            'column_css_class'  => 'lecture-number',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('lectures')->__('Title'),
            'index'     => 'title'
        ));

        $this->addColumn('default_course_number', array(
            'header'    => Mage::helper('lectures')->__('Default Course Number'),
            'index'     => 'default_course_number'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $this->_getProductId(),'lectureid' => $row->getId(),'back' => 'edit','tab' => 'product_info_tabs_lectures','lectureselected' => 1));
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/lecturesGrid', array('_current' => true));
    }
}
