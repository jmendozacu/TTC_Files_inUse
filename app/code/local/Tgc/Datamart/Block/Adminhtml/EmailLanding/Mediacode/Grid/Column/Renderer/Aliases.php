<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_Adminhtml_EmailLanding_Mediacode_Grid_Column_Renderer_Aliases
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $dataArr = array();
        $rowData = $row->getData($this->getColumn()->getIndex());
        if (is_array($rowData)) {
            $dataArr = $rowData;
        }
        $data = join($this->getColumn()->getSeparator() ? $this->getColumn()->getSeparator() : ', ', $dataArr);
        return $data;
    }
}
