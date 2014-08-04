<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_CmsSetup_Model_HashCleaner
{
    private $_shouldRemoveHash = false;

    public function allowCleanUp()
    {
        $this->_shouldRemoveHash = true;
    }

    public function cleanUp(Varien_Event_Observer $observer)
    {
        $object = $observer->getEvent()->getObject();

        if ($this->_shouldRemoveHash && ($object instanceof Mage_Cms_Model_Block || $object instanceof Mage_Cms_Model_Page)) {
            $object->setHash(null);
        }
    }
}
