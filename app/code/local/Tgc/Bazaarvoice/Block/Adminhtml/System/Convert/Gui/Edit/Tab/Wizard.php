<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Block_Adminhtml_System_Convert_Gui_Edit_Tab_Wizard
    extends Mage_Adminhtml_Block_System_Convert_Gui_Edit_Tab_Wizard
{
    private function _getProfile()
    {
        $profile = Mage::registry('current_convert_profile');

        if (!$profile) {
            $profile = Mage::getModel('dataflow/profile');
        }

        return $profile;
    }

    public function isReview()
    {
        $profile = $this->_getProfile();

        return $profile->getEntityType() == Tgc_Bazaarvoice_Model_Convert_Adapter_Review::ENTITY;
    }
}
