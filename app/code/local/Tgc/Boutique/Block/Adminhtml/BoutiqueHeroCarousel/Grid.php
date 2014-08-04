<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @boutique    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_BoutiqueHeroCarousel_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('boutiqueHeroCarouselGrid');
        $this->_defaultLimit = 100;
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setNoFilterMassactionColumn(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_Boutique_Model_Resource_BoutiqueHeroCarousel_Collection */
        $collection = Mage::getModel('tgc_boutique/boutiqueHeroCarousel')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_boutique')->__('Item ID'),
            'align'     => 'left',
            'width'     => '30px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('tgc_boutique')->__('Is Active'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array('0' => 'No', '1' => 'Yes'),
        ));

        $this->addColumn('user_type', array(
            'header'    => Mage::helper('tgc_boutique')->__('User Type'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'user_type',
            'type'      => 'options',
            'options'   => Mage::getModel('tgc_boutique/source_userType')->toOptionArray(),
        ));

        $this->addColumn('boutique_id', array(
            'header'    => Mage::helper('tgc_boutique')->__('Boutique'),
            'index'     => 'boutique_id',
            'align'     => 'left',
            'width'     => '50px',
            'type'      => 'options',
            'options'   => Mage::getModel('tgc_boutique/source_boutiques')->toItemArray(),
        ));

        $this->addColumn('boutique_page_id', array(
            'header'    => Mage::helper('tgc_boutique')->__('Boutique Page'),
            'index'     => 'boutique_page_id',
            'align'     => 'left',
            'width'     => '50px',
            'type'      => 'options',
            'options'   => Mage::getModel('tgc_boutique/source_pages')->toItemArray(),
        ));

        $this->addColumn('tab_title', array(
            'header'    => Mage::helper('tgc_boutique')->__('Tab Title'),
            'align'     => 'left',
            'width'     => '150px',
            'index'     => 'tab_title',
        ));

        $this->addColumn('store', array(
            'header'    => Mage::helper('tgc_boutique')->__('Store'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'store',
            'type'      => 'options',
            'options'   => Mage::getModel('tgc_boutique/source_store')->toOptionArray(),
        ));

        $this->addColumn('active_from', array(
            'header'    => Mage::helper('tgc_boutique')->__('Active From'),
            'width'     => '75px',
            'index'     => 'active_from',
            'type'      => 'datetime',
            'gmtoffset' => true,
        ));

        $this->addColumn('active_to', array(
            'header'    => Mage::helper('tgc_boutique')->__('Active To'),
            'width'     => '75px',
            'index'     => 'active_to',
            'type'      => 'datetime',
            'gmtoffset' => true,
        ));

        $this->addColumn('sort_order', array(
            'header'    => Mage::helper('tgc_boutique')->__('Sort Order'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'sort_order',
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('tgc_boutique')->__('Action'),
            'width'     => '60',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('tgc_boutique')->__('Edit'),
                    'url'       => array('base' => '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('tgc_boutique')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tgc_boutique')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('boutiqueHeroCarousel');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_boutique')->__('Delete item(s)'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_boutique')->__('Really delete the selected carousel item(s)?')
        ));

        $statuses = array('1' => 'Active', '0' => 'Inactive');
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('tgc_boutique')->__('Change status'),
            'url'   => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('tgc_boutique')->__('Status'),
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
