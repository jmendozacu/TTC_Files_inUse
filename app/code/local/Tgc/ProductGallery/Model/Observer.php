<?php
/**
 * ProductGallery
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ProductGallery
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_ProductGallery_Model_Observer
{
    /**
     * Override gallery field type renderer template
     *
     * @param Varien_Event_Observer $observer
     * @return \Tgc_ProductGallery_Model_Observer
     */
    public function setGalleryTemplate(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block) {
            $block->setTemplate('tgc_productgallery/catalog/product/helper/gallery.phtml');
        }
        return $this;
    }
}
