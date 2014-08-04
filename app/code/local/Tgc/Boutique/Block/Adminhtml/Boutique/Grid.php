<?php
/**
 * Boutique
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Boutique
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Boutique_Block_Adminhtml_Boutique_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('boutiqueGrid');
        $this->_defaultLimit = 50;
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setNoFilterMassactionColumn(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var $collection Tgc_Boutique_Model_Resource_BoutiquePages_Collection */
        $collection = Mage::getModel('tgc_boutique/boutique')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('tgc_boutique')->__('Page ID'),
            'align'     => 'left',
            'width'     => '30px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('tgc_boutique')->__('Boutique Name'),
            'align'     => 'left',
            'width'     => '100px',
            'index'     => 'name',
        ));

        $this->addColumn('url_key', array(
            'header'    => Mage::helper('tgc_boutique')->__('URL Key'),
            'align'     => 'left',
            'width'     => '70px',
            'index'     => 'url_key',
        ));

        $this->addColumn('is_default', array(
            'header'    => Mage::helper('tgc_boutique')->__('Is Default'),
            'align'     => 'left',
            'width'     => '70px',
            'index'     => 'is_default',
        ));

        $this->addColumn('disable_carousel', array(
            'header'    => Mage::helper('tgc_boutique')->__('Disable Carousel'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'disable_carousel',
            'type'      => 'options',
            'options'   => array('0' => 'No', '1' => 'Yes'),
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
        $this->getMassactionBlock()->setFormFieldName('boutique');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tgc_boutique')->__('Delete boutique(s)'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tgc_boutique')->__('Really delete the selected boutique(s)?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
