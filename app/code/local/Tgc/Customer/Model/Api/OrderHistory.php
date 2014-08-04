<?php
/**
 * OrderHistory API Model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Customer
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Customer_Model_Api_OrderHistory extends SoapClient
{
    const ORDER_HISTORY_WS_WSDL = 'http://ttcdax01:8090/orders.asmx?wsdl';

    private $_client;
    private $_wsdl;
    private $_datas = array();

    public function __construct()
    {
        $wsdl = Mage::getStoreConfig('dax/api/wsdl');
        $this->_wsdl = $wsdl ? $wsdl : self::ORDER_HISTORY_WS_WSDL;
        $this->_client = new SoapClient($this->_wsdl);
    }

    private function _getClient()
    {
        return $this->_client;
    }

    public function getOrders($daxCustomerId)
    {
        $method = 'GetOrderHistory';
        $params = array('CustAccount' => $daxCustomerId);
        try {
            $result = $this->_getClient()->$method($params);
        } catch (SoapFault $e) {
            Mage::logException($e);
            return array();
        }

        $tmp = (array)@$result;
        if (!isset($result) || empty($result) || empty($tmp)) {
            return array();
        }

        $xml = $result->GetOrderHistoryResult->any;
        $element = new SimpleXMLElement($xml);
        $orders = $element->Table1;
        $datas = array();

        foreach ($orders as $order) {
            $array = $this->_simpleXmlToArray($order);
            $datas[$array['salesid']][] = $array;
        }

        foreach ($datas as $key => $data) {
            if (!$this->_isInsideTimeframe($data)) {
                unset($datas[$key]);
                continue;
            } else {
                $datas[$key]['created_at'] = $this->_convertDate($datas[$key][0]['CREATEDDATETIME']);
            }
        }
        krsort($datas);
        $this->_datas = $datas;

        return $this->_datas;
    }

    private function _isInsideTimeframe($data)
    {
        $view = $this->_getCurrentView();
        switch ($view) {
            case Tgc_Customer_Block_Order_History::VIEW_ALL:
                return true;
            case Tgc_Customer_Block_Order_History::VIEW_BY_30_DAYS:
                $displayLast = 60 * 60 * 24 * 30;
                break;
            case Tgc_Customer_Block_Order_History::VIEW_BY_60_DAYS:
                $displayLast = 60 * 60 * 24 * 60;
                break;
            case Tgc_Customer_Block_Order_History::VIEW_BY_6_MONTHS:
                $displayLast = 60 * 60 * 24 * 180;
                break;
            case Tgc_Customer_Block_Order_History::VIEW_BY_1_YEAR:
                $displayLast = 60 * 60 * 24 * 365;
                break;
            case Tgc_Customer_Block_Order_History::VIEW_BY_3_YEARS:
                $displayLast = 60 * 60 * 24 * 365 * 3;
                break;
            default:
                $displayLast = 60 * 60 * 24 * 30;
        }

        $displayTime = time() - $displayLast;

        foreach ($data as $item) {
            if (isset($item['CREATEDDATETIME'])) {
                return strtotime($item['CREATEDDATETIME']) > $displayTime;
            }
        }

        return false;
    }

    public function getDetails($orderId)
    {
        return $this->_getOrderDetails($orderId);
    }

    protected function _getOrderDetails($salesId)
    {
        $method = 'GetOrder';
        $params = array('SalesID' => $salesId);
        try {
            $result = $this->_getClient()->$method($params);
        } catch (SoapFault $e) {
            Mage::logException($e);
        }

        $tmp = (array)@$result;
        if (!isset($result) || empty($result) || empty($tmp)) {
            return array();
        }

        $xml = $result->GetOrderResult->any;
        $element = new SimpleXMLElement($xml);
        $details = $element->Table1;
        $array = $this->_simpleXmlToArray($details);
        $array['CREATEDDATETIME'] = $this->_convertDate($array['CREATEDDATETIME']);

        return $array;
    }

    public function getTracking($orderId)
    {
        return $this->_getOrderTrack($orderId);
    }

    protected function _getOrderTrack($orderId)
    {
        $method = 'GetTrackingNumber';
        $params = array('OrderID' => $orderId);
        try {
            $result = $this->_getClient()->$method($params);
        } catch (SoapFault $e) {
            Mage::logException($e);
        }

        $tmp = (array)@$result;
        if (!isset($result) || empty($result) || empty($tmp)) {
            return array();
        }

        $xml = $result->GetTrackingNumberResult->any;
        $element = new SimpleXMLElement($xml);
        $details = $element->Table1;
        $array = $this->_simpleXmlToArray($details);

        return $array;
    }

    private function _simpleXmlToArray($xml)
    {
        $array = (array)$xml;
        foreach ($array as $key => $value){
            if(strpos(@get_class($value), "SimpleXML") !== false){
                $array[$key] = $this->_simpleXmlToArray($value);
            }
        }

        return $array;
    }

    private function _convertDate($date)
    {
        if (empty($date)) {
            return false;
        }

        return date('m/d/Y', strtotime($date));
    }

    private function _getViewParam()
    {
        $request = Mage::app()->getFrontController()->getRequest();
        $view = $request->getParam(Tgc_Customer_Block_Order_History::PARAM_NAME);

        return $view;
    }

    private function _getCurrentView()
    {
        $view = $this->_getViewParam();

        $availableViews = array(
            Tgc_Customer_Block_Order_History::VIEW_BY_30_DAYS,
            Tgc_Customer_Block_Order_History::VIEW_BY_60_DAYS,
            Tgc_Customer_Block_Order_History::VIEW_BY_6_MONTHS,
            Tgc_Customer_Block_Order_History::VIEW_BY_1_YEAR,
            Tgc_Customer_Block_Order_History::VIEW_BY_3_YEARS,
            Tgc_Customer_Block_Order_History::VIEW_ALL,
        );

        if (empty($view) || !in_array($view, $availableViews)) {
            return Tgc_Customer_Block_Order_History::DEFAULT_VIEW_BY;
        }

        return $view;
    }

    public function getOrder($orderId, $daxId)
    {
        $orders = $this->getOrders($daxId);

        foreach ($orders as $id => $order) {
            if ($id == $orderId) {
                $array = $this->_simpleXmlToArray($order);
                $array['CREATEDDATETIME'] = $array['created_at'];

                return $array;
            }
            continue;
        }

        return array();
    }
}
