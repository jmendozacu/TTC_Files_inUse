<?php
/**
 * User: mhidalgo
 * Date: 11/03/14
 * Time: 14:58
 */
class Tgc_Zmag_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('zmagGrid');
        $this->setDefaultSort('zmag_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tgc_zmag/zmag')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('zmag_id',
            array(
                'header' => 'ID',
                'align' => 'right',
                'width' => '50px',
                'index' => 'zmag_id',
            ));
        $this->addColumn('publication_id',
            array(
                'header' => 'Publication ID',
                'align' => 'left',
                'index' => 'publication_id',
            ));
        $this->addColumn('page_instructions', array(
            'header' => 'Page Instructions',
            'align' => 'left',
            'index' => 'page_instructions',
        ));
        $this->addColumn('icon', array(
            'header' => 'Icon',
            'align' => 'left',
            'index' => 'icon',
        ));
        $this->addColumn('website_id', array(
            'header' => 'Website',
            'align' => 'left',
            'index' => 'website_id',
            'type' => 'options',
            'options' => Mage::helper('tgc_zmag')->getWebsiteOptions()
        ));
        $this->addColumn('status', array(
            'header' => 'Status',
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => array(Tgc_Zmag_Model_Zmag::STATUS_DISABLED => 'Disabled', Tgc_Zmag_Model_Zmag::STATUS_ENABLED => 'Enabled'),
        ));

        $groups = Mage::getResourceModel('customer/group_collection')
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

        $this->addColumn('group', array(
            'header'    =>  Mage::helper('customer')->__('Group'),
            'width'     =>  '100',
            'index'     =>  'customer_type',
            'type'      =>  'options',
            'options'   =>  $groups,
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('tgc_zmag')->__('Action'),
            'width' => '60',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('tgc_zmag')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                ),
                array(
                    'caption' => Mage::helper('tgc_zmag')->__('Delete'),
                    'url' => array('base' => '*/*/delete'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('tgc_zmag')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tgc_zmag')->__('XML'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}