<?php
/**
 * Reviews in my account
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Block_Abstract extends Mage_Review_Block_Customer_List
{
    const API_ENDPOINT   = 'api.bazaarvoice.com/data/';
    const API_VERSION    = '5.4';
    const API_FORMAT     = 'json';
    const XML_API_KEY    = 'bazaarvoice/conversations_api/api_key';
    const AUTHORS_TYPE   = 'authors';

    protected $_apiUrl;
    protected $_directionVarName      = 'dir';
    protected $_direction             = 'desc';
    protected $_paramsMemorizeAllowed = true;
    protected $_author;

    public function _construct()
    {
        parent::_construct();

        $this->_apiUrl = 'http://';

        $environment = Mage::getStoreConfig('bazaarvoice/general/environment');
        if ($environment == 'staging') {
            $this->_apiUrl .= 'stg.';
        }

        $this->_apiUrl .= self::API_ENDPOINT;
    }

    protected function _getAuthorUrl()
    {
        $url = $this->_apiUrl . self::AUTHORS_TYPE . '.' . self::API_FORMAT;
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $query = array(
            'PassKey'    => $this->_getApiKey(),
            'ApiVersion' => self::API_VERSION,
            'Filter'     => 'Id:' . $customer->getWebUserId(),
            'Stats'      => 'Questions,Answers,Reviews',
            'Limit'      => '99',
        );
        $queryString = http_build_query($query);

        return $url . '?' . $queryString;
    }

    protected function _getAuthor()
    {
        if (isset($this->_author)) {
            return $this->_author;
        }

        $url = $this->_getAuthorUrl();
        $author = $this->_makeRequest($url);
        $this->_author = isset($author[0]) ? $author[0] : null;

        return $this->_author;
    }

    public function getDirectionVarName()
    {
        return $this->_directionVarName;
    }

    protected function _formatContributorRank($rank)
    {
        $rank = str_replace('_', ' ', $rank);

        return ucwords(strtolower($rank));
    }

    public function getCurrentDirection()
    {
        $dir = $this->_getData('_current_review_direction');
        if ($dir) {
            return $dir;
        }

        $directions = array('asc', 'desc');
        $dir = strtolower($this->getRequest()->getParam($this->getDirectionVarName()));
        if ($dir && in_array($dir, $directions)) {
            if ($dir == $this->_direction) {
                Mage::getSingleton('customer/session')->unsReviewDirection();
            } else {
                $this->_memorizeParam('review_direction', $dir);
            }
        } else {
            $dir = Mage::getSingleton('customer/session')->getReviewDirection();
        }

        if (!$dir || !in_array($dir, $directions)) {
            $dir = $this->_direction;
        }
        $this->setData('_current_review_direction', $dir);

        return $dir;
    }

    protected function _memorizeParam($param, $value)
    {
        $session = Mage::getSingleton('customer/session');
        if ($this->_paramsMemorizeAllowed && !$session->getParamsMemorizeDisabled()) {
            $session->setData($param, $value);
        }
        return $this;
    }

    public function getAvailableOrders()
    {
        return array(
            'desc' => Mage::helper('tgc_bv')->__('Date: Descending'),
            'asc' => Mage::helper('tgc_bv')->__('Date: Ascending'),
        );
    }

    public function getOrderUrl($dir)
    {
        $urlParams = array();
        $urlParams['_current'] = true;
        $urlParams['_escape'] = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query'] = array($this->getDirectionVarName() => $dir);

        return $this->getUrl('*/*/*', $urlParams);
    }

    protected function _getApiKey()
    {
        return Mage::getStoreConfig(self::XML_API_KEY);
    }

    protected function _getProductFromIdentifier($productId = null)
    {
        return Mage::helper('tgc_bv')->getProductFromProductExternalId($productId);
    }

    protected function _convertDate($date)
    {
        return date('F j, Y', strtotime($date));
    }

    protected function _makeRequest($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $url,
            CURLOPT_USERAGENT      => 'Teachco infograbber',
        ));

        $response = curl_exec($curl);
        $decoded = Zend_Json::decode($response);
        $results = (array)$decoded['Results'];

        return $results;
    }
}
