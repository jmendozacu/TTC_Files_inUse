<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Adminhtml_Catalog_Product_Edit_Tab_Lectures_Buttons_Delete extends Mage_Adminhtml_Block_Widget_Button
{
    protected $_onClickUrl;

    protected $_canDelete;

    public function __construct()
    {
        $request = Mage::app()->getFrontController()->getAction()->getRequest();
        $productId = $request->getParam('id');
        $canDelete = $request->getParam('lectureid') ? true : false;
        $this->_canDelete = $canDelete;
        if($this->_canDelete) {
            $lectureId = $request->getParam('lectureid');
            $this->_onClickUrl = "confirmSetLocation('Are you sure?', '" . $this->getUrl('*/*/deleteLecture', array('id' => $productId,'lectureid'=> $lectureId,'back' => 'edit','tab' => 'product_info_tabs_lectures')) . "')";
        }
    }

    protected function _beforeToHtml()
    {
        if($this->_canDelete) {
            $this->setId('group_fields_lectures_delete')
                ->setOnClick($this->_onClickUrl)
                ->setType('button')
                ->setClass('delete')
                ->setLabel(Mage::helper('adminhtml')->__('Delete Lecture'));
        }
    }

    protected function _toHtml()
    {
        $html= "";

        if($this->_canDelete) {
            $html .= parent::_toHtml();
        }

        return $html;
    }
}