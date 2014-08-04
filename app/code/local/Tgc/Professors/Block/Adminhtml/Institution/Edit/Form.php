<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Block_Adminhtml_Institution_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
    implements Tgc_Professors_Block_Adminhtml_ModelFormInterface
{
    private $_model;

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'            => 'edit_form',
            'action'        => $this->getUrl('*/*/save'),
            'method'        => 'post',
            'use_container' => true,
        ));

        $fieldset = $form->addFieldset('base', array(
            'legend' => $this->__('General'),
        ));

        $fieldset->addField('id', 'hidden', array(
            'name' => 'id'
        ));

        $fieldset->addField('name', 'text', array(
            'name'  => 'name',
            'label' => $this->__('Name'),
        ));

         $this->setForm($form);
    }

    protected function _initFormValues()
    {
        try {
            $values = $this->_getModel()->getData();
            $values['id'] = $this->_getModel()->getId();
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

    private function _getModel()
    {
        if (!$this->_model) {
            throw new LogicException('Model is undefined.');
        }

        return $this->_model;
    }
}