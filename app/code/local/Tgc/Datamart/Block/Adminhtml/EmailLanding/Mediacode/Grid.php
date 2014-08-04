<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_Adminhtml_EmailLanding_Mediacode_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('landingMediacodeGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tgc_datamart/emailLanding_mediacode')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => $this->__('ID'),
            'align'     => 'right',
            'index'     => 'entity_id',
            'width'     => '50px'
        ));

        $this->addColumn('media_code', array(
            'header'    => $this->__('Media Code'),
            'align'     => 'left',
            'index'     => 'media_code',
        ));

        $this->addColumn('media_code_aliases', array(
            'header'        => $this->__('Aliases'),
            'index'         => 'media_code_aliases',
            'sortable'      => false,
            'renderer'      => 'tgc_datamart/adminhtml_emailLanding_mediacode_grid_column_renderer_aliases',
            'filter_condition_callback' => array($this, '_filterAliasCondition')
        ));

        $adCodesOptions = array();
        $adCodesCollection = Mage::getModel('tgc_price/adCode')->getCollection()
            ->addFieldToSelect('code')
            ->setOrder('code', Varien_Data_Collection::SORT_ORDER_ASC);
        foreach ($adCodesCollection->getColumnValues('code') as $code) {
            $adCodesOptions[$code] = $code;
        }
        $this->addColumn('ad_code', array(
            'header'        => $this->__('Ad Code'),
            'index'         => 'ad_code',
            'type'          => 'options',
            'options'       => $adCodesOptions
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
                    'field'   => 'entity_id'
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

    protected function _filterAliasCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $collection->addAliasFilter($value);
    }

    /**
     * Row click url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('entity_id' => $row->getId()));
    }
}
