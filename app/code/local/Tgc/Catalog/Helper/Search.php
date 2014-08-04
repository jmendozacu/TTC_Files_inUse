<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Helper_Search extends Mage_CatalogSearch_Helper_Data
{
    /**
     * Retrieve result page url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @param   string $query
     * @return  string
     */
    public function getResultUrl($query = null)
    {
        return $this->_getUrl('search/result', array(
            '_query' => array(self::QUERY_VAR_NAME => $query),
            '_secure' => $this->_getApp()->getFrontController()->getRequest()->isSecure()
        ));
    }
}