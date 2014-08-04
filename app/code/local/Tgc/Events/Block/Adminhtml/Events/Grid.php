<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Adminhtml_Events_Grid extends FME_Events_Block_Adminhtml_Events_Grid
{

  protected function _prepareColumns()
  {
      parent::_prepareColumns();

      /* We are overwriting the column named event_venu that was created in parent::_prepareColumns() */
      $this->addColumn('event_venu', array(
          'header'    => Mage::helper('events')->__('Location'),
          'align'     =>'left',
          'index'     => 'event_venu',
          'type'      => 'options',
          'options'   => Mage::getModel('tgc_events/locations')->getCollection()->setOrder('sort_order','ASC')->toOptionHash(),
      ),'event_title');

      $this->addColumn('event_type', array(
          'header'    => Mage::helper('events')->__('Type'),
          'align'     =>'left',
          'index'     => 'event_type',
          'type'      => 'options',
          'options'   => Mage::getModel('tgc_events/types')->getCollection()->toOptionHash(),
      ),'event_title');

      $this->addColumnsOrder('event_type','event_title');
      $this->addColumnsOrder('event_type','event_title');
      $this->sortColumnsByOrder();
      return $this;
  }

}
