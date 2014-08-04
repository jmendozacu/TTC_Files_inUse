<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Adminhtml_Locations_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('locationsGrid');
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
      $collection = Mage::getModel('tgc_events/locations')->getCollection();
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
          'header'    => Mage::helper('events')->__('Location'),
          'align'     =>'left',
          'index'     => 'location',
      ));
	  
	  $this->addColumn('location_code', array(
          'header'    => Mage::helper('events')->__('Location Code'),
          'align'     =>'left',
          'index'     => 'location_code',
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
	  
	  $this->addColumn('location_image', array(
		  'header'=> Mage::helper('events')->__('Location-Image'),
		  'width' => '50px',
		  'index' => 'location_image',
		  'renderer' => 'Tgc_Events_Block_Adminhtml_Renderer_Locationimage',
		  'filter' => false
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
        $this->setMassactionIdField('location_id');
        $this->getMassactionBlock()->setFormFieldName('locations');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('events')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('events')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('events/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('location_status', array(
             'label'=> Mage::helper('events')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'location_status',
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
