<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_AkamaiContent_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('akamaiContentGrid');
        $this->_defaultLimit = 200;
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_DigitalLibrary_Model_Resource_AkamaiContent_Collection */
        $collection = Mage::getModel('tgc_dl/akamaiContent')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Content ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('course_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Course Id'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'course_id',
        ));

        $this->addColumn('guidebook_file_name', array(
            'header'    => Mage::helper('tgc_dl')->__('Guidebook File Name'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'guidebook_file_name',
        ));

        $this->addColumn('transcript_file_name', array(
            'header'    => Mage::helper('tgc_dl')->__('Transcript File Name'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'transcript_file_name',
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('tgc_dl')->__('Action'),
            'width'     => '60',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('tgc_dl')->__('Edit'),
                    'url'       => array('base' => '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('tgc_dl')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tgc_dl')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('akamaiContent');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_dl')->__('Delete content'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_dl')->__('Really delete the selected content?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
