<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Block_Adminhtml_Professor_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('profs/professor_collection');
        $this->setCollection($collection);

        parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'index'  => 'professor_id',
            'width'  => '50px'
        ));
        $this->addColumn('first_name', array(
            'header' => $this->__('First name'),
            'index'  => 'first_name',
        ));
        $this->addColumn('last_name', array(
            'header' => $this->__('Last name'),
            'index'  => 'last_name',
        ));
        $this->addColumn('title', array(
            'header' => $this->__('Title'),
            'index'  => 'title',
        ));
        $this->addColumn('qual', array(
            'header' => $this->__('Qualification'),
            'index'  => 'qual',
        ));
        $this->addColumn('rank', array(
            'header' => $this->__('Ranking'),
            'index'  => 'rank',
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