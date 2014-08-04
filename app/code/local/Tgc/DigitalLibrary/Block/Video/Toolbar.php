<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_Video_Toolbar extends Tgc_DigitalLibrary_Block_List_Toolbar
{
    public function _construct()
    {
        parent::_construct();
        $this->addOrderToAvailableOrders('downloaded', 'Downloaded');
    }
}
