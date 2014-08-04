<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Block_Adminhtml_EmailLanding_Design_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('emailLandingDesignGrid');
        $this->_defaultLimit = 20;
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_Datamart_Model_Resource_EmailLanding_Design_Collection */
        $collection = Mage::getModel('tgc_datamart/emailLanding_design')->getCollection();
        //join the header
        $collection->getSelect()->joinLeft(
            array('header' => $collection->getTable('cms/block')),
            'header.identifier = main_table.header_id',
            array('header' => 'header.title')
        );
        //join the footer
        $collection->getSelect()->joinLeft(
            array('footer' => $collection->getTable('cms/block')),
            'footer.identifier = main_table.footer_id',
            array('footer' => 'footer.title')
        );
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('category', array(
            'header'    => Mage::helper('tgc_datamart')->__('Category'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'category',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('tgc_datamart')->__('Title'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'title',
        ));

        $this->addColumn('description', array(
            'header'    => Mage::helper('tgc_datamart')->__('Meta Description'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'description',
        ));

        $this->addColumn('keywords', array(
            'header'    => Mage::helper('tgc_datamart')->__('Meta Keywords'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'keywords',
        ));

        $this->addColumn('header', array(
            'header'    => Mage::helper('tgc_datamart')->__('Header Block'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'header',
            'filter_index' => 'header.title',
        ));

        $this->addColumn('footer', array(
            'header'    => Mage::helper('tgc_datamart')->__('Footer Block'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'footer',
            'filter_index' => 'footer.title',
        ));

        $this->addColumn('landing_page_type', array(
            'header'    => Mage::helper('tgc_datamart')->__('Landing Page Type'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'landing_page_type',
            'type'      => 'options',
            'options'   => Mage::getModel('tgc_datamart/source_landingPage_type')->toOptionArray(),
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
        $this->getMassactionBlock()->setFormFieldName('emailLanding_design');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_datamart')->__('Delete design(s)'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_datamart')->__('Really delete the selected design(s)?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
