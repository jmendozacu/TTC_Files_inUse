<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_Block_List extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('podcast/podcast')
                        ->getCollection()
                        ->addStoreFilter()
                        ->setOrder('created_time ', 'desc');
        $this->setCollection($collection);
    }
    
    public function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('podcast/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }
        
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}

    
