<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_Model_Layouts{
    
	protected $_options;
    
    public function toOptionArray()
    {
        if (!$this->_options) {
            $layouts = array();
            $node = Mage::getConfig()->getNode('global/cms/layouts') 
                    ? Mage::getConfig()->getNode('global/cms/layouts') 
                    : Mage::getConfig()->getNode('global/page/layouts');

            foreach ($node->children() as $layoutConfig) {
                    $this->_options[] = array(
                       'value'=>(string)$layoutConfig->template,
                       'label'=>(string)$layoutConfig->label
                    );
            }		
        }
        return $this->_options;
    }
}
