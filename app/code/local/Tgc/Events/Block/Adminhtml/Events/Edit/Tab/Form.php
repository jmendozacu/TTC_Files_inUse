<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Adminhtml_Events_Edit_Tab_Form extends FME_Events_Block_Adminhtml_Events_Edit_Tab_Form
{
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $form = $this->getForm();

        $fieldset = $form->getElement('events_form');

        $fieldset->removeField('event_venu');
        $fieldset->removeField('event_video');

        $fieldset->addField('event_venu', 'select', array(
          'name'      => 'event_venu',
          'label'     => Mage::helper('events')->__('Location'),
          'class'     => '',
          'required'  => true,
          'values'    => Mage::getModel('tgc_events/locations')->getCollection()->setOrder('sort_order','ASC')->toOptionArray(),
        ), 'event_title');

        $fieldset->addField('event_type', 'select', array(
          'label'     => Mage::helper('events')->__('Type'),
          'class'     => '',
          'required'  => true,
          'name'      => 'event_type',
          'values'    => Mage::getModel('tgc_events/types')->getCollection()->toOptionArray(),
        ), 'event_venu');

        $fieldset->addField('event_short_description', 'text', array(
            'label'     => Mage::helper('events')->__('Short Description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'event_short_description',
        ),'event_type');

        $fieldset->addField('event_website_link', 'text', array(
            'label'     => Mage::helper('events')->__('Website Link'),
            'class'     => '',
            'required'  => false,
            'name'      => 'event_website_link',
            'after_element_html' => "<p><small>".Mage::helper('events')->__('Enter full url, i.e. http://www.myeventwebsite.com')."</small></p>"
        ),'event_short_description');

        $fieldset->addField('event_date_location_description', 'text', array(
            'label'     => Mage::helper('events')->__('Date/Location Description'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'event_date_location_description',
            'after_element_html' => "<p><small>".Mage::helper('events')->__('i.e. "September 14-23 - Locations Vary"')."</small></p>"
        ),'event_website_link');

        $fieldset->addField('global_featured_event', 'select', array(
            'label'     => Mage::helper('events')->__('Global Featured Event'),
            'class'     => '',
            'required'  => true,
            'name'      => 'global_featured_event',
            'values'    => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
            'after_element_html' => "<p><small>".Mage::helper('events')->__('Appears as featured event on Events landing page.')."</small></p>"
        ), 'event_date_location_description');

        $fieldset->addField('location_featured_event', 'select', array(
            'label'     => Mage::helper('events')->__('Location Featured Event'),
            'class'     => '',
            'required'  => true,
            'name'      => 'location_featured_event',
            'values'    => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
            'after_element_html' => "<p><small>".Mage::helper('events')->__('Appears as featured event on Location landing page')."</small></p>"
        ), 'global_featured_event');

        if ( Mage::getSingleton('adminhtml/session')->getEventsData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getEventsData());
            Mage::getSingleton('adminhtml/session')->setEventsData(null);
        }
        elseif ( Mage::registry('events_data') )
        {
            $form->setValues(Mage::registry('events_data')->getData());
        }
        return $this;
    }
}
