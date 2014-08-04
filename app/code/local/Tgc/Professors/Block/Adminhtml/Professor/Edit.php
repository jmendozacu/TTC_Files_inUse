<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Block_Adminhtml_Professor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'profs';
        $this->_controller = 'adminhtml_professor';
        $this->_mode       = 'edit';
        $this->_headerText = $this->__('Professor');
        $id = $this->getRequest()->getParam('id');
        $this->addButton('delete', array(
            'label'  => $this->__('Delete'),
            'onclick' => "setLocation('{$this->jsQuoteEscape($this->getUrl('*/*/delete', array('id' => $id)))}')",
            'class' => 'delete',
        ));
    }
}