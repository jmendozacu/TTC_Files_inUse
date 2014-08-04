<?php

class Tgc_Adcoderouter_Block_Adminhtml_Adcoderedirectlist_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _construct()
    {
        parent::_construct();

        $this->setId('adcoderedirectlist_form');
        $this->setTitle($this->__('Ad Code Redirect Form'));
    }

    protected function _getWebsiteOptions()
    {
        $options = array();
        foreach (Mage::app()->getWebsites() as $website) {
            $storeIds = $website->getStoreIds();
            $options[array_pop($storeIds)] = $website->getName();
        }

        return $options;
    }

    protected function _getPageOptions()
    {
        $options = Mage::getResourceModel('tgc_cms/page_collection')
            ->setOrder('title', 'asc')
            ->toOptionArrayById();

        array_unshift($options, array('label' => null, 'value' => null));

        return $options;
    }

    protected function _getProfessorOptions()
    {
        $options = Mage::getResourceModel('profs/professor_collection')
            ->setOrder('last_name', 'asc')
            ->toOptionArray();

        array_unshift($options, array('label' => null, 'value' => null));

        return $options;
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('adcoderouter_redirects');

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('adcoderouter')->__('Ad Code Redirect Information'),
            'class'     => 'fieldset-wide',
        ));

        $fieldset->addType('customdate','Tgc_Adcoderouter_Block_Adminhtml_Widget_Form_Element_Renderer_Customdate');

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('search_expression', 'text', array(
            'name'      => 'search_expression',
            'label'     => Mage::helper('adcoderouter')->__('Request Path'),
            'title'     => Mage::helper('adcoderouter')->__('Request Path'),
            'required'  => true,
        ));

        $fieldset->addField('ad_type', 'select', array(
            'name' => 'ad_type',
            'label' => Mage::helper('adcoderouter')->__('Ad Type'),
            'title' => Mage::helper('adcoderouter')->__('Ad Type'),
            'options' => Mage::getSingleton('adcoderouter/field_source_adtype')->getAllOptions(),
        ));

        $fieldset->addField('header_cms_desktop', 'select', array(
            'name' => 'header_cms_desktop',
            'label' => Mage::helper('adcoderouter')->__('Header Static Block (Desktop)'),
            'title' => Mage::helper('adcoderouter')->__('Header Static Block (Desktop)'),
            'values' => Mage::getModel('tgc_datamart/adminhtml_system_config_source_cms_block')->toOptionArray(),
        ));

        $fieldset->addField('header_cms_mobile', 'select', array(
            'name' => 'header_cms_mobile',
            'label' => Mage::helper('adcoderouter')->__('Header Static Block (Mobile)'),
            'title' => Mage::helper('adcoderouter')->__('Header Static Block (Mobile)'),
            'values' => Mage::getModel('tgc_datamart/adminhtml_system_config_source_cms_block')->toOptionArray(),
        ));

        $fieldset->addField('dax_key', 'text', array(
            'name'      => 'dax_key',
            'label'     => Mage::helper('adcoderouter')->__('Dax Key'),
            'title'     => Mage::helper('adcoderouter')->__('Dax Key'),
            'class'     => 'validate-digits',
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('start_date', 'customdate', array(
            'name'         => 'start_date',
            'label'        => Mage::helper('adcoderouter')->__('Start Date'),
            'title'        => Mage::helper('adcoderouter')->__('Start Date'),
            'image'        => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso,
            'class' => 'validate-date',
            'required'  => true,
        ));

        $fieldset->addField('end_date', 'customdate', array(
            'name'          => 'end_date',
            'label'         => Mage::helper('adcoderouter')->__('End Date'),
            'title'         => Mage::helper('adcoderouter')->__('End Date'),
            'image'         => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'        => $dateFormatIso,
            'class'         => 'validate-date',
            'required'  => true,
        ));

        $fieldset->addField('description', 'text', array(
            'name'      => 'description',
            'label'     => Mage::helper('adcoderouter')->__('Description'),
            'title'     => Mage::helper('adcoderouter')->__('Description'),
        ));

        $fieldset->addField('ad_code', 'text', array(
            'name'      => 'ad_code',
            'label'     => Mage::helper('adcoderouter')->__('Ad Code'),
            'title'     => Mage::helper('adcoderouter')->__('Ad Code'),
            'class'     => 'validate-digits required-entry',
            'required'  => true,
        ));

        $fieldset->addField('ad_code_from_param', 'text', array(
            'name'      => 'ad_code_from_param',
            'label'     => Mage::helper('adcoderouter')->__('Ad Code from Query Parameter'),
            'title'     => Mage::helper('adcoderouter')->__('Ad Code from Query Parameter'),
            'required'  => false,
        ));

        $fieldset->addField('course_id', 'text', array(
            'name'      => 'course_id',
            'label'     => Mage::helper('adcoderouter')->__('Course ID'),
            'title'     => Mage::helper('adcoderouter')->__('Course ID'),
            'class'     => 'validate-digits required-adcode-field',
        ));

        $fieldset->addField('professor_id', 'select', array(
            'name'      => 'professor_id',
            'label'     => Mage::helper('adcoderouter')->__('Professor'),
            'title'     => Mage::helper('adcoderouter')->__('Professor'),
            'values'    => $this->_getProfessorOptions(),
        ));

        $fieldset->addField('category_id', 'text', array(
            'name'      => 'category_id',
            'label'     => Mage::helper('adcoderouter')->__('Category ID'),
            'title'     => Mage::helper('adcoderouter')->__('Category ID'),
            'class'     => 'validate-digits',
        ));

        $fieldset->addField('cms_page_id', 'select', array(
            'name'      => 'cms_page_id',
            'label'     => Mage::helper('adcoderouter')->__('CMS Page'),
            'title'     => Mage::helper('adcoderouter')->__('CMS Page'),
            'values'    => $this->_getPageOptions(),
        ));

        $fieldset->addField('store_id', 'select', array(
            'name' => 'store_id',
            'label' => Mage::helper('adcoderouter')->__('Store'),
            'title' => Mage::helper('adcoderouter')->__('Store'),
            'required' => true,
            'options' => $this->_getWebsiteOptions(),
        ));

        $fieldset->addField('pid', 'text', array(
            'name'      => 'pid',
            'label'     => Mage::helper('adcoderouter')->__('Welcome Message'),
            'title'     => Mage::helper('adcoderouter')->__('Welcome Message'),
            'class'     => 'spacecode',
        ));

        $fieldset->addField('welcome_subtitle', 'text', array(
            'name'      => 'welcome_subtitle',
            'label'     => Mage::helper('adcoderouter')->__('Welcome Subtitle'),
            'title'     => Mage::helper('adcoderouter')->__('Welcome Subtitle'),
        ));

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array(
                'add_images' => true,
                'add_widgets' => true,
                'add_variables' => true,
            )
        );

        $fieldset->addField('more_details', 'editor', array(
            'label'     => Mage::helper('adcoderouter')->__('More Details'),
            'title'     => Mage::helper('adcoderouter')->__('More Details'),
            'name'      => 'more_details',
            'class'     => 'spacecode',
            'style'     => 'height:18em;',
            'config'    => $wysiwygConfig
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}