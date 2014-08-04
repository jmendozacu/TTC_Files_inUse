<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Block_Adminhtml_Partners_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('partnersGrid');
        $this->_defaultLimit = 20;
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setNoFilterMassactionColumn(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_Cms_Model_Resource_Partners_Collection */
        $collection = Mage::getModel('tgc_cms/partners')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_cms')->__('Item ID'),
            'align'     => 'left',
            'width'     => '30px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('tgc_cms')->__('Is Active'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array('0' => 'No', '1' => 'Yes'),
        ));

        $this->addColumn('alt_text', array(
            'header'    => Mage::helper('tgc_cms')->__('Alternate Text'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'alt_text',
        ));

        $this->addColumn('description', array(
            'header'    => Mage::helper('tgc_cms')->__('Description'),
            'align'     => 'left',
            'width'     => '100px',
            'index'     => 'description',
        ));

        $this->addColumn('sort_order', array(
            'header'    => Mage::helper('tgc_cms')->__('Sort Order'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'sort_order',
        ));

        $this->addColumn('store', array(
            'header'    => Mage::helper('tgc_cms')->__('Store'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'store',
            'type'      => 'options',
            'options'   => Mage::getModel('tgc_cms/source_store')->toOptionArray(),
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('tgc_cms')->__('Action'),
            'width'     => '60',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('tgc_cms')->__('Edit'),
                    'url'       => array('base' => '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('tgc_cms')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tgc_cms')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('partners');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_cms')->__('Delete item(s)'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_cms')->__('Really delete the selected partners item(s)?')
        ));

        $statuses = array('1' => 'Active', '0' => 'Inactive');
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('tgc_cms')->__('Change status'),
            'url'   => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('tgc_cms')->__('Status'),
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
