<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_Adminhtml_EmailLanding_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('landingBannerGrid');
        $this->setDefaultSort('banner_id');
        $this->setDefaultDir('DESC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tgc_datamart/emailLanding_banner')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('banner_id', array(
            'header'    => $this->__('ID'),
            'align'     => 'right',
            'index'     => 'banner_id',
            'width'     => '50px'
        ));

        $this->addColumn('title', array(
            'header'    => $this->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $adCodesOptions = array();
        $adCodesCollection = Mage::getModel('tgc_price/adCode')->getCollection()
            ->addFieldToSelect('code')
            ->setOrder('code', Varien_Data_Collection::SORT_ORDER_ASC);
        foreach ($adCodesCollection->getColumnValues('code') as $code) {
            $adCodesOptions[$code] = $code;
        }
        $this->addColumn('ad_codes', array(
            'header'        => $this->__('Ad Code'),
            'index'         => 'ad_codes',
            'type'          => 'options',
            'options'       => $adCodesOptions,
            'sortable'      => false,
            'filter_condition_callback' => array($this, '_filterAdCodeCondition'),
            'width'         => '250px'
        ));

        $this->addColumn('action', array(
            'header'    => $this->__('Action'),
            'width'     => '50px',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => $this->__('Edit'),
                    'url'     => array('base' => '*/*/edit'),
                    'field'   => 'banner_id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true
        ));

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterAdCodeCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $collection->addAdCodeFilter($value);
    }

    /**
     * Row click url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('banner_id' => $row->getId()));
    }
}
