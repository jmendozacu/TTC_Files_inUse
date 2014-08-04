<?php
/**
 * Gift Card Account customization
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     GiftCardAccount
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Grid extends Enterprise_GiftCardAccount_Block_Adminhtml_Giftcardaccount_Grid
{
    /**
     * Bump up memory limit and default limit
     */
    public function __construct()
    {
        ini_set('memory_limit', '2048M');
        $this->_defaultLimit = 200;

        parent::__construct();
    }
}
