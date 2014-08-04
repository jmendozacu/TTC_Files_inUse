<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_AnonymousUpsell_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('anonymousUpsellGrid');
        $this->_defaultLimit = 200;
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_Datamart_Model_Resource_EmailLanding_Collection */
        $collection = Mage::getModel('tgc_datamart/anonymousUpsell')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_datamart')->__('Entity ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('subject_id', array(
            'header'    => Mage::helper('tgc_datamart')->__('Subject ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'subject_id',
        ));

        $this->addColumn('course_id', array(
            'header'    => Mage::helper('tgc_datamart')->__('Course ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'course_id',
        ));

        $this->addColumn('sort_order', array(
            'header'    => Mage::helper('tgc_datamart')->__('Sort Order'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'sort_order',
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('tgc_datamart')->__('Action'),
            'width'     => '60',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('tgc_datamart')->__('Edit'),
                    'url'       => array('base' => '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('tgc_datamart')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tgc_datamart')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('anonymousUpsell');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_datamart')->__('Delete upsell(s)'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_datamart')->__('Really delete the selected upsell(s)?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
