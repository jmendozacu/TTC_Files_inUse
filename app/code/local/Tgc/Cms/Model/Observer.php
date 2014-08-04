<?php
/**
 * Cms Observer
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Model_Observer
{
    /**
     * Adds a new element to the form that determines if the title is shown at the top of the page.
     * By default, title is shown in two places: (1) in the <title> tags (2) title that is visible to user at top of the page.
     * This field allows user to choose to hide the visible title at top of page that users normally see.
     * @param Varien_Event_Observer $observer
     */
    public function addFormFieldToDisablePageTitle(Varien_Event_Observer $observer)
    {
        $form = $observer->getEvent()->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $yesNoOptions = Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray();

        $fieldset->addField('page_title_is_page_headline', 'select', array(
                'label'     => Mage::helper('tgc_cms')->__('Show Page Title as Page Headline'),
                'title'     => Mage::helper('tgc_cms')->__('Show Page Title as Page Headline'),
                'name'      => 'page_title_is_page_headline',
                'values'   => $yesNoOptions,
            ),
            'title'
        );
    }
}