<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Adminhtml_Data_Form_Element_File extends Varien_Data_Form_Element_File
{


    public function getAfterElementHtml()
    {
        return $this->getGuidebooksHtml($this->getValue());
    }

    private function getGuidebooksHtml($filename)
    {
        $fullHtml = null;

        if($filename) {
            $url = $this->_helper()->getGuidebooksUrl($filename);
            $fullHtml = '<br />Guidebook already uploaded: <a href="' . $url . '" target="_blank">' . $filename . "</a>";
        }

        return $fullHtml;
    }

    private function _helper()
    {
        return Mage::helper('lectures');
    }
}