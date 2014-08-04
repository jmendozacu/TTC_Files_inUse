<?php
/**
 * Transactional emails abstract class for order related emails
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
abstract class Tgc_StrongMail_Model_Email_Sales_Order_Abstract extends Tgc_StrongMail_Model_Email_Abstract
{
    /**
     * Current order
     *
     * @var Mage_Sales_Model_Order
     */
    private $_order;

    /**
     * Order setter
     *
     * @param Mage_Sales_Model_Order $order
     * @return Tgc_StrongMail_Model_Email_Sales_Order_Abstract
     */
    public function setOrder(Mage_Sales_Model_Order $order)
    {
        $this->_order = $order;
        return $this;
    }

    /**
     * Order getter
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Store ID getter
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->getOrder()->getStoreId();
    }

    /**
     * Returns additional parameters for transactional email template in key-value style.
     *
     * @return array
     */
    abstract protected function _getAdditionalParams();
}