<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Block_Adminhtml_Professor_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
    implements Tgc_Professors_Block_Adminhtml_ModelFormInterface
{
    const FIELD_CATEGORY    = 'category_id';
    const FIELD_INSTITUTION = 'teaching_at_ids';
    const FIELD_PHOTO       = 'photo';
    const FIELD_ALMA_MATERS = 'alma_mater_ids';

    private $_model;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'            => 'edit_form',
            'action'        => $this->getUrl('*/*/save'),
            'method'        => 'post',
            'use_container' => true,
            'enctype'      => 'multipart/form-data'
        ));

        $this->_addGeneralFieldset($form);
        $this->_addContacsFieldset($form);
        $this->_addTestimonialFieldset($form);

        $this->setForm($form);
    }

    private function _addGeneralFieldset(Varien_Data_Form $form)
    {
        $fieldset = $form->addFieldset('base', array(
            'legend' => $this->__('General'),
        ));

        $fieldset->addType('image', 'Tgc_Professors_Block_Adminhtml_Professor_Edit_Form_Element_Image');

        $fieldset->addField('id', 'hidden', array(
            'name' => 'id'
        ));

        $fieldset->addField('first_name', 'text', array(
            'name'  => 'first_name',
            'label' => $this->__('First Name'),
        ));

        $fieldset->addField('last_name', 'text', array(
            'name'  => 'last_name',
            'label' => $this->__('Last Name'),
        ));

        $fieldset->addField('url_key', 'text', array(
            'name'  => 'url_key',
            'label' => 'URL Key',
        ));

        $fieldset->addField('title', 'text', array(
            'name'  => 'title',
            'label' => $this->__('Title'),
        ));

        $fieldset->addField('qual', 'text', array(
            'name'  => 'qual',
            'label' => $this->__('Qualification'),
        ));

        $fieldset->addField('photo', 'image', array(
            'name'  => self::FIELD_PHOTO,
            'label' => $this->__('Photo'),
        ));

        $fieldset->addField('bio', 'editor', array(
            'name'   => 'bio',
            'label'  => $this->__('Biography'),
            'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(array(
                'add_variables' => false,
                'add_widgets'   => false,
                'add_images'    => false
            ))
        ));

        $institutions = Mage::getResourceModel('profs/institution_collection')->toOptionArray();
        array_unshift($institutions, array('label' => null, 'value' => null));

        $fieldset->addField(self::FIELD_INSTITUTION, 'select', array(
            'name'    => self::FIELD_INSTITUTION . '[]',
            'label'   => $this->__('Institution'),
            'values'  => $institutions,
            'comment' => $this->__('Institutions where professor currently is teaching.'),
        ));

        $fieldset->addField('alma_mater_ids', 'select', array(
            'name'    => self::FIELD_ALMA_MATERS . '[]',
            'label'   => $this->__('Alma Mater'),
            'values'  => $institutions,
        ));

        $fieldset->addField('category_id', 'select', array(
            'name' => self::FIELD_CATEGORY,
            'label' => $this->__('Category'),
            'values' => Mage::getModel('profs/source_category')->toOptionArray(),
        ));

        $fieldset->addField('rank', 'text', array(
            'name'  => 'rank',
            'label' => $this->__('Rank'),
        ));

        $fieldset->addField('quote', 'textarea', array(
            'name'  => 'quote',
            'label' => $this->__('Quote'),
        ));
    }

    private function _addContacsFieldset(Varien_Data_Form $form)
    {
        $fieldset = $form->addFieldset('contacts', array(
            'legend' => $this->__('Contacts'),
        ));

        $fieldset->addField('email', 'text', array(
            'name'  => 'email',
            'label' => $this->__('E-mail'),
        ));

        $fieldset->addField('facebook', 'text', array(
            'name'  => 'facebook',
            'label' => $this->__('Facebook'),
        ));

        $fieldset->addField('twitter', 'text', array(
            'name'  => 'twitter',
            'label' => 'Twitter'
        ));

        $fieldset->addField('pinterest', 'text', array(
            'name'  => 'pinterest',
            'label' => $this->__('Pinterest')
        ));

        $fieldset->addField('youtube', 'text', array(
            'name'  => 'youtube',
            'label' => $this->__('YouTube')
        ));
    }

    private function _addTestimonialFieldset(Varien_Data_Form $form)
    {
        $fieldset = $form->addFieldset('testimonial_fieldset', array(
            'legend' => $this->__('Testimonials'),
        ));

        $fieldset->addField('testimonial', 'editor', array(
            'name'   => 'testimonial',
            'label'  => $this->__('Content'),
            'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(array(
                'add_variables' => false,
                'add_widgets'   => false,
                'add_images'    => false
            ))
        ));
    }

    protected function _initFormValues()
    {
        try {
            $model = $this->_getModel();
            $values = $model->getData();
            $values['id'] = $model->getId();
            $values[self::FIELD_ALMA_MATERS] = $model->getAlmaMaterIds();
            $values[self::FIELD_INSTITUTION] = $model->getTeachingAtIds();
            $this->getForm()->addValues($values);
        } catch (LogicException $e) {
            // it's ok
        }
    }

    public function setValuesFromModel(Mage_Core_Model_Abstract $model)
    {
        $this->_model = $model;

        return $this;
    }

    /**
     *
     * @return Tgc_Professors_Model_Professor
     * @throws LogicException
     */
    private function _getModel()
    {
        if (!$this->_model) {
            throw new LogicException('Model is undefined.');
        }

        return $this->_model;
    }
}