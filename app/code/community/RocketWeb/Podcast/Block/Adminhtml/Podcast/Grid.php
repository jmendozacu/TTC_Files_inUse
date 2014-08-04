<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_Block_Adminhtml_Podcast_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('podcastGrid');
        $this->setDefaultSort('created_time');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('podcast/podcast')->getCollection();
        $store = $this->_getStore();
        if ($store->getId()) {
            $collection->addStoreFilter($store);
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('podcast_id', array(
            'header'    => Mage::helper('podcast')->__('ID'),
            'align'     =>'right',
            'width'     => '15px',
            'index'     => 'podcast_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('podcast')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));


        $this->addColumn('short_content', array(
            'header'    => Mage::helper('podcast')->__('Short Description'),
            'index'     => 'short_content',
        ));

        $this->addColumn('created_time', array(
            'header'    => Mage::helper('podcast')->__('Created at'),
            'index'     => 'created_time',
            'type'      => 'datetime',
            'width'     => '160px',
            'gmtoffset' => true,
            'default'   => ' -- '
        ));
        
        $this->addColumn('status', array(
            'header'    => Mage::helper('podcast')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => Mage::getModel('podcast/status')->toOptionArray(),
        ));

        $this->addColumn('action', array(
           'header'    =>  Mage::helper('podcast')->__('Action'),
           'width'     => '60px',
           'type'      => 'action',
           'getter'    => 'getId',
           'actions'   => array(
                                array(
                                  'caption'   => Mage::helper('podcast')->__('Edit'),
                                  'url'       => array('base'=> '*/*/edit'),
                                  'field'     => 'id'
                                )
                        ),
                        'filter'    => false,
                        'sortable'  => false,
                        'index'     => 'stores',
                        'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('podcast_id');
        $this->getMassactionBlock()->setFormFieldName('podcast');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('podcast')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('podcast')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('podcast/status')->toOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('podcast')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('podcast')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        
        return $this;
    }

    public function getRowUrl($row)
    {
            return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}