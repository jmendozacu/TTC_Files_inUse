<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */

class RocketWeb_Podcast_Model_Category extends Varien_Object{

    public function toOptionArray()
    {

        $category_list = array(
            Mage::helper('podcast')->__('All Categories'),
            Mage::helper('podcast')->__('Comedy'),
            Mage::helper('podcast')->__('News & Politics'),
            Mage::helper('podcast')->__('Technology'),
            Mage::helper('podcast')->__('Arts'),
            Mage::helper('podcast')->__('Business'),
            Mage::helper('podcast')->__('Education'),
            Mage::helper('podcast')->__('Games & Hobbies'),
            Mage::helper('podcast')->__('Government & Organisations'),
            Mage::helper('podcast')->__('Health'),
            Mage::helper('podcast')->__('Kids & Family'),
            Mage::helper('podcast')->__('Music'),
            Mage::helper('podcast')->__('Religion & Spirituality'),
            Mage::helper('podcast')->__('Science & Medicine'),
            Mage::helper('podcast')->__('Society & Culture'),
            Mage::helper('podcast')->__('Sports & Recreation'),
            Mage::helper('podcast')->__('TV & Film')
        );

        return array_combine($category_list,$category_list);
    }
}
