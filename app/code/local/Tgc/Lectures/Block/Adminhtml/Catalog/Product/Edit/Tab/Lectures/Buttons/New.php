<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Adminhtml_Catalog_Product_Edit_Tab_Lectures_Buttons_New extends Mage_Adminhtml_Block_Widget_Button
{

    protected $_onClickUrl;

    protected $_canCreateNew;

    public function __construct()
    {
        $request = Mage::app()->getFrontController()->getAction()->getRequest();
        $productId = $request->getParam('id');
        $canCreateNew = $request->getParam('lectureid') ? true : false;
        $this->_canCreateNew = $canCreateNew;
        $this->_onClickUrl = "setLocation('" . $this->getUrl('*/*/edit', array('id' => $productId,'back' => 'edit','tab' => 'product_info_tabs_lectures','newlecture' => 1,'lectureselected' => 0))  . "')";
    }

    protected function _beforeToHtml()
    {
        $this->setId('group_fields_lectures_new')
            ->setOnClick($this->_onClickUrl)
            ->setType('button')
            ->setClass('add')
            ->setLabel(Mage::helper('adminhtml')->__('Add New Lecture'));
    }

    protected function _toHtml()
    {
        $html= "";

        if($this->_canCreateNew) {
            $html .= parent::_toHtml();
        }

        return $html;
    }
}