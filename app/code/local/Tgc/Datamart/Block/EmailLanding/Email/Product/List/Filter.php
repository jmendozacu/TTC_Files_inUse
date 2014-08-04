<?php

class Tgc_Datamart_Block_EmailLanding_Email_Product_List_Filter extends Mage_Core_Block_Abstract
{
    /**
     * Preparing global layout
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $productListBlock = $this->getLayout()->getBlock('product_list');
        if ($productListBlock) {
            $collection = $productListBlock->getLoadedProductCollection();
            $collection->addAttributeToFilter('course_id', array('in' => $this->getCourseIds()));
        }
        parent::_prepareLayout();
    }

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
                    Tgc_Datamart_Model_Source_LandingPage_Type::EMAIL_VALUE
                )
            );
        }

        return $this->getData('course_ids');
    }

    /**
     * Landing page category getter
     *
     * @return string
     */
    public function getLandingPageCategory()
    {
        if (!$this->hasData('landing_page_category')) {
            $this->setLandingPageCategory(Mage::registry('landing_page_category'));
        }

        return $this->getData('landing_page_category');
    }
}
