<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Adminhtml_Catalog_Product_Edit_Tab_Lectures_Form_Element_Textcustom extends Varien_Data_Form_Element_Text
{
    public function getLabelHtml($idSuffix = '')
    {
        if (!is_null($this->getLabel())) {
            $html = '<label for="'.$this->getHtmlId() . $idSuffix . '">' . $this->_escape($this->getLabel())
                . ( $this->getRequiredcustom() ? ' <span class="requiredcustom">*</span>' : '' ) . '</label>' . "\n";
        } else {
            $html = '';
        }
        return $html;
    }
}