<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Podcast_Block_Podcast extends RocketWeb_Podcast_Block_Podcast
{
    protected $_ids       = array();
    
    public function getPodcast($podcast_id = 0)
    {
        if(!$podcast_id)
        {
            $podcast_url = $this->getRequest()->getParam('identifier',0);
            $podcast_id = Mage::helper('podcast')->decodeUrl($podcast_url);
        }
        $podcast = Mage::getModel('tgc_podcast/podcast')->load($podcast_id);

        if (!$podcast->getId()) {
            $podcast_url = $this->getRequest()->getParam('identifier',0);
            $podcast = Mage::getModel('podcast/podcast')->load($podcast_url, 'url_key');
        }
        
        return $podcast;
    }

    public function getPodcastBlock()
    {
        $block = $this->getLayout()->createBlock('tgc_podcast/list', microtime());

        return $block;
    }

    public function getCurrentPodcastList()
    {
        if(!count($this->_ids))
        {
            $collection = $this->getPodcastBlock()->_prepareLayout()->getCollection();
            foreach($collection as $podcast)
            {
                $this->_ids[] = $podcast->getPodcastId();
            }
        }
        return $this->_ids;
    }

    /*
     * Given an array and a value in the array, find the prev and next values
     */
    public function getPrevNext($aArray, $value)
    {
        // Flip the array so we can use the Podcast Id to find it's position in the array
        $aIndices = array_flip($aArray);
        $i = $aIndices[$value];
        if ($i > 0) $prev = $aArray[$i-1]; //use previous key in aArray
        if ($i < count($aIndices)-1) $next = $aArray[$i+1]; //use next key in aArray
        if(!isset($prev)) $prev = -1;
        if(!isset($next)) $next = -1;
        return array($prev,$next);
    }

}