<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Adminhtml_Catalog_Product_Edit_Tab_Lectures_Buttons_Save extends Mage_Adminhtml_Block_Widget_Button
{
    protected $_saveAndContinueUrl;

    public function __construct()
    {
        $request = Mage::app()->getFrontController()->getAction()->getRequest();
        $productId = $request->getParam('id');
        $lectureId = $request->getParam('lectureid');
        $linkParams = array('id' => $productId,'back' => 'edit','tab' => 'product_info_tabs_lectures');

        if($lectureId) {
            $linkParams['lectureid'] = $lectureId;
        }

        $this->_saveAndContinueUrl = "saveAndContinueEdit('" . $this->getUrl('*/*/save', $linkParams) . "')";
    }

    protected function _beforeToHtml()
    {
        $saveAndCOntinueEditUrl = 'saveAndContinueEdit(\''.$this->getSaveAndContinueUrl().'\')';
        $this->setId('group_fields_lectures_new')
            ->setOnClick($this->_saveAndContinueUrl)
            ->setType('button')
            ->setClass('save')
            ->setLabel(Mage::helper('adminhtml')->__('Save Lecture'));
    }
}