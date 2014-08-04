<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Podcast_Block_List extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('podcast/podcast')
            ->getCollection()
            ->addStoreFilter();
        $this->setCollection($collection);
    }

    public function _prepareLayout()
    {
        //parent::_prepareLayout();

        $toolbar = $this->getToolbarBlock();
        // called prepare sortable parameters
        $collection = $this->getCollection();

        // use sortable parameters
        if ($orders = $this->getAvailableOrders())
        {
            $toolbar->setAvailableOrders($orders);
        }

        if ($sort = $this->getSortBy())
        {
            $toolbar->setDefaultOrder($sort);
        }

        if ($dir = $this->getDefaultDirection())
        {
            $toolbar->setDefaultDirection($dir);
        }

        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);

        $this->getCollection()->load();

        return $this;
    }

    public function getToolbarBlock()
    {
        $block = $this->getLayout()->createBlock('tgc_podcast/toolbar', microtime());

        return $block;
    }

    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

}
