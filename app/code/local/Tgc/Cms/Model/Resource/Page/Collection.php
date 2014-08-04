<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Cms_Model_Resource_Page_Collection extends Mage_Cms_Model_Resource_Page_Collection
{
    public function toOptionArrayById()
    {
        return $this->_toOptionArray('page_id', 'title');
    }
}