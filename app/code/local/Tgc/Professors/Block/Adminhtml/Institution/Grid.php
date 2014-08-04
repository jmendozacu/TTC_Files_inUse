<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Block_Adminhtml_Institution_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('profs/institution_collection');
        $this->setCollection($collection);

        parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'index'  => 'institution_id',
            'width'  => '50px'
        ));
        $this->addColumn('name', array(
            'header' => $this->__('Name'),
            'index'  => 'name',
        ));
        $this->addColumn('action', array(
            'header'    => $this->__('Action'),
            'width'     => '50px',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(array(
                'caption' => $this->__('Edit'),
                'url'     => array('base' => '*/*/edit'),
                'field'   => 'id'
            )),
            'filter'    => false,
            'sortable'  => false,
        ));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}