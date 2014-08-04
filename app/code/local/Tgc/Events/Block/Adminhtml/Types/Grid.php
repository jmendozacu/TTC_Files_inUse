<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Adminhtml_Types_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('typesGrid');
      $this->setDefaultSort('entity_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _getStore()
  {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('tgc_events/types')->getCollection();
	  $store = $this->_getStore();
	  if ($store->getId())
	  {
		  $collection->addStoreFilter($store);
	  }
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('entity_id', array(
          'header'    => Mage::helper('events')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'entity_id',
      ));
	  
      $this->addColumn('location', array(
          'header'    => Mage::helper('events')->__('Type'),
          'align'     =>'left',
          'index'     => 'type',
      ));

	  $this->addColumn('type_icon', array(
		  'header'=> Mage::helper('events')->__('Type Icon'),
		  'width' => '50px',
		  'index' => 'type_icon',
		  'renderer' => 'Tgc_Events_Block_Adminhtml_Renderer_Typeicon',
		  'filter' => false
	  ));

      $this->addColumn('is_active', array(
          'header'    => Mage::helper('events')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'is_active',
          'type'      => 'options',
          'options'   => array(
              0 => Mage::helper('cms')->__('Disabled'),
              1 => Mage::helper('cms')->__('Enabled')
          ),
      ));

      $this->addColumn('action', array(
		  'header'    =>  Mage::helper('events')->__('Action'),
		  'width'     => '100',
		  'type'      => 'action',
		  'getter'    => 'getId',
		  'actions'   => array(
			  array(
				  'caption'   => Mage::helper('events')->__('Edit'),
				  'url'       => array('base'=> '*/*/edit'),
				  'field'     => 'id'
			  )
		  ),
		  'filter'    => false,
		  'sortable'  => false,
		  'index'     => 'stores',
		  'is_system' => true,
      ));
		
	  $this->addExportType('*/*/exportCsv', Mage::helper('events')->__('CSV'));
	  $this->addExportType('*/*/exportXml', Mage::helper('events')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('type_id');
        $this->getMassactionBlock()->setFormFieldName('types');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('events')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('events')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('events/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('type_status', array(
             'label'=> Mage::helper('events')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'type_status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('events')->__('Status'),
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
