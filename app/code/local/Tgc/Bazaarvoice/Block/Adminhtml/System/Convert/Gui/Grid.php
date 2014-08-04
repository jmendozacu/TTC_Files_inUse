<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Block_Adminhtml_System_Convert_Gui_Grid extends Mage_Adminhtml_Block_System_Convert_Gui_Grid
{
    protected function _prepareColumns()
    {
        $this->addColumn('profile_id', array(
            'header'    => Mage::helper('adminhtml')->__('ID'),
            'width'     => '50px',
            'index'     => 'profile_id',
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('adminhtml')->__('Profile Name'),
            'index'     => 'name',
        ));
        $this->addColumn('direction', array(
            'header'    => Mage::helper('adminhtml')->__('Profile Direction'),
            'index'     => 'direction',
            'type'      => 'options',
            'options'   => array('import'=>'Import', 'export'=>'Export'),
            'width'     => '120px',
        ));
        $this->addColumn('entity_type', array(
            'header'    => Mage::helper('adminhtml')->__('Entity Type'),
            'index'     => 'entity_type',
            'type'      => 'options',
            'options'   => array('product'=>'Products', 'customer'=>'Customers', 'bv_review'=>'Bazaarvoice Review'),
            'width'     => '150px',
        ));

        $this->addColumn('store_id', array(
            'header'    => Mage::helper('adminhtml')->__('Store'),
            'type'      => 'options',
            'align'     => 'center',
            'index'     => 'store_id',
            'type'      => 'store',
            'width'     => '150px',
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('adminhtml')->__('Created At'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'created_at',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('adminhtml')->__('Updated At'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'updated_at',
        ));

        $this->addColumn('action', array(
            'header'    => Mage::helper('adminhtml')->__('Action'),
            'width'     => '60px',
            'align'     => 'center',
            'sortable'  => false,
            'filter'    => false,
            'type'      => 'action',
            'actions'   => array(
                array(
                    'url'       => $this->getUrl('*/*/edit') . 'id/$profile_id',
                    'caption'   => Mage::helper('adminhtml')->__('Edit')
                )
            )
        ));

        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }
}
