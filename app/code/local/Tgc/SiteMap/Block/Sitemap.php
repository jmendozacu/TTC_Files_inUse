<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Tgc_SiteMap
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SiteMap_Block_Sitemap extends Mage_Core_Block_Template
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setTemplate('tgc_sitemap/sitemap.phtml');
    }
    
    /**
     * Get tgc sitemap model
     * 
     * @return Tgc_SiteMap_Model_Sitemap
     */
    public function getModel()
    {
        return Mage::getModel('tgc_sitemap/sitemap');
    }
}
