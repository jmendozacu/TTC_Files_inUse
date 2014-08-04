<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Block_EmailLanding_Radio_Product_List extends Tgc_Datamart_Block_EmailLanding_Buffet_Product_List
{
    /**
     * Course ids getter
     *
     * @return array
     */
    public function getCourseIds()
    {
        if (!$this->hasData('course_ids')) {
            $this->setCourseIds(
                Mage::getResourceModel('tgc_datamart/emailLanding')->getCourseIdsByCategory(
                    $this->getLandingPageCategory(),
                    Tgc_Datamart_Model_Source_LandingPage_Type::RADIO_VALUE
                )
            );
        }

        return $this->getData('course_ids');
    }
}
