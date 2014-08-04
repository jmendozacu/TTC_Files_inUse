<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Podcast_Block_Podcast_Relatedcourses extends Mage_Catalog_Block_Product_Abstract
{
    protected $_courseCollection;

    public function getPodcast($podcast_id = 0)
    {
        if(!$podcast_id)
        {
            $podcast_url = $this->getRequest()->getParam('identifier',0);
            $podcast_id = Mage::helper('podcast')->decodeUrl($podcast_url);
        }
        $podcast = Mage::getModel('tgc_podcast/podcast')->load($podcast_id);
        
        return $podcast;
    }

    public function getRelatedCourses()
    {
        $this->_courseCollection = $this->getPodcast()->getRelatedCourses();
        if (Mage::helper('catalog')->isModuleEnabled('Mage_Checkout')) {
            Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($this->_courseCollection,
                Mage::getSingleton('checkout/session')->getQuoteId()
            );

            $this->_addProductAttributesAndPrices($this->_courseCollection);
        }
        $this->_courseCollection->load();
        return $this->_courseCollection;
    }

}