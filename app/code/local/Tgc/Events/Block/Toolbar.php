<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Block_Toolbar extends FME_Events_Block_Toolbar
{
	public function _construct()
	{
		parent::_construct();
	    // Customize to use our own toolbar.phtml instead of the one from catalog/product
        $this->_template = 'events/toolbar.phtml';
    }
}
