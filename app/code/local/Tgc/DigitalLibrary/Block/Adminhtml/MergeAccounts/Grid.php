<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Adminhtml_MergeAccounts_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('mergeAccountsGrid');
        $this->_defaultLimit = 200;
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_DigitalLibrary_Model_Resource_AccessRights_Collection */
        $collection = Mage::getModel('tgc_dl/mergeAccounts')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Merge ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('dax_customer_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Dax Customer Id'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'dax_customer_id',
        ));

        $this->addColumn('mergeto_dax_customer_id', array(
            'header'    => Mage::helper('tgc_dl')->__('Merge To Account'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'mergeto_dax_customer_id',
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
        $this->getMassactionBlock()->setFormFieldName('mergeAccounts');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_dl')->__('Delete merge account(s)'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_dl')->__('Really delete the selected merge account(s)?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
