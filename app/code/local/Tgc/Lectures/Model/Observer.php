<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Model_Observer
{

    const INPUT_TYPE_FILE_CUSTOM = 'customfile';

    public function addAdditionalElementRenderersCustom(Varien_Event_Observer $observer)
    {
        $originalTypes = $observer->getResponse()->getTypes();
        $originalTypes[self::INPUT_TYPE_FILE_CUSTOM] = 'Tgc_Lectures_Block_Adminhtml_Data_Form_Element_File';
        $observer->getResponse()->setTypes($originalTypes);
    }
}