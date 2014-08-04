<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Block_Adminhtml_EmailUnsubscribe_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('emailUnsubscribeGrid');
        $this->_defaultLimit = 200;
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_Dax_Model_Resource_EmailUnsubscribe_Collection */
        $collection = Mage::getModel('tgc_dax/emailUnsubscribe')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_dax')->__('Entity ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('web_key', array(
            'header'    => Mage::helper('tgc_dax')->__('Web Key'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'web_key',
        ));

        $this->addColumn('customer_id', array(
            'header'    => Mage::helper('tgc_dax')->__('Customer ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'customer_id',
        ));

        $this->addColumn('email', array(
            'header'    => Mage::helper('tgc_dax')->__('Email'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'email',
        ));

        $this->addColumn('unsubscribe_date', array(
            'header'    => Mage::helper('tgc_dax')->__('Unsubscribe Date'),
            'width'     => '100px',
            'index'     => 'unsubscribe_date',
            'type'      => 'date',
            'gmtoffset' => true,
        ));

        $this->addColumn('email_campaign', array(
            'header'    => Mage::helper('tgc_dax')->__('Email Campaign'),
            'align'     => 'left',
            'width'     => '180px',
            'index'     => 'email_campaign',
        ));

        $this->addColumn('is_archived', array(
            'header'    => Mage::helper('tgc_dax')->__('Is Archived'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'is_archived',
            'type'      => 'options',
            'options'   => array('0' => 'No', '1' => 'Yes'),
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('tgc_dax')->__('Action'),
            'width'     => '60',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('tgc_dax')->__('Edit'),
                    'url'       => array('base' => '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('tgc_dax')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tgc_dax')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('emailUnsubscribe');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_dax')->__('Delete unsubscribe(s)'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_dax')->__('Really delete the selected unsubscribe(s)?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
