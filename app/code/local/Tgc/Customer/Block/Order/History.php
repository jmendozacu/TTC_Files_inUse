<?php
/**
 * Order History Block.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Customer
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Block_Order_History extends Mage_Core_Block_Template
{
    const DEFAULT_VIEW_BY       = '30-days';
    const VIEW_BY_30_DAYS       = '30-days';
    const VIEW_BY_60_DAYS       = '60-days';
    const VIEW_BY_6_MONTHS      = '6-months';
    const VIEW_BY_1_YEAR        = '1-year';
    const VIEW_BY_3_YEARS       = '3-years';
    const VIEW_ALL              = 'all';
    const PARAM_NAME            = 'view';

    public function __construct()
    {
        Mage_Core_Block_Template::__construct();
        $this->setTemplate('sales/order/history.phtml');
    }

    public function getOrders()
    {
        $model = Mage::getModel('tgc_customer/order_history');
        $orders = $model->getOrders();

        return $orders;
    }

    public function getOrderItems()
    {
        $orderId = $this->getOrderId();
        $daxId = $this->getDaxId();
        $model = Mage::getModel('tgc_customer/order_history');
        $order = (array)$model->getOrder($orderId, $daxId);
        $array = array();
        foreach ($order as $key => $datas) {
            if (is_numeric($key)) {
                $array[$key] = $datas;
            }
        }

        return $array;
    }

    public function getOrderDetails()
    {
        $orderId = $this->getOrderId();
        $model = Mage::getModel('tgc_customer/order_history');
        $details = (array)$model->getOrderDetails($orderId);

        return new Varien_Object($details);
    }

    public function getOrderTracking()
    {
        $orderId = $this->getOrderId();
        $model = Mage::getModel('tgc_customer/order_history');
        $tracking = (array)$model->getShippingTracking($orderId);

        return new Varien_Object($tracking);
    }

    public function getCurrentView()
    {
        $view = $this->_getViewParam();

        $availableViews = array(
            self::VIEW_BY_30_DAYS,
            self::VIEW_BY_60_DAYS,
            self::VIEW_BY_6_MONTHS,
            self::VIEW_BY_1_YEAR,
            self::VIEW_BY_3_YEARS,
            self::VIEW_ALL,
        );

        if (empty($view) || !in_array($view, $availableViews)) {
            return self::DEFAULT_VIEW_BY;
        }

        return $view;
    }

    public function isViewCurrent($url)
    {
        $current = $this->getCurrentView();
        $currentUrl = Mage::getUrl('*/*/*', array(self::PARAM_NAME => $current));

        return $url == $currentUrl;
    }

    public function getAvailableViews()
    {
        $availableViews = array(
            '30 Days'  => self::VIEW_BY_30_DAYS,
            '60 Days'  => self::VIEW_BY_60_DAYS,
            '6 Months' => self::VIEW_BY_6_MONTHS,
            '1 Year'   => self::VIEW_BY_1_YEAR,
            '3 Years'  => self::VIEW_BY_3_YEARS,
            'Show All' => self::VIEW_ALL,
        );

        $array = array();
        foreach ($availableViews as $name => $value) {
            $array[$name] = Mage::getUrl('*/*/*', array(self::PARAM_NAME => $value));
        }

        return $array;
    }

    private function _getViewParam()
    {
        $request = Mage::app()->getFrontController()->getRequest();
        $view = $request->getParam(self::PARAM_NAME);

        return $view;
    }
}
