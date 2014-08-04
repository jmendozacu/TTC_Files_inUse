<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Catalog_Model_Url_Rewrite_Request extends Enterprise_UrlRewrite_Model_Url_Rewrite_Request
{
    /**
     * Process redirect (R) and permanent redirect (RP)
     *
     * Saves query string
     *
     * @return Mage_Core_Model_Url_Rewrite_Request
     */
    protected function _processRedirectOptions()
    {
        $isPermanentRedirectOption = $this->_rewrite->hasOption('RP');

        $external = substr($this->_rewrite->getTargetPath(), 0, 6);
        if ($external === 'http:/' || $external === 'https:') {
            $destinationStoreCode = $this->_app->getStore($this->_rewrite->getStoreId())->getCode();
            $this->_setStoreCodeCookie($destinationStoreCode);
            $this->_sendRedirectHeaders($this->_rewrite->getTargetPath(), $isPermanentRedirectOption);
        }

        $targetUrl = $this->_request->getBaseUrl() . '/' . $this->_rewrite->getTargetPath();

        $storeCode = $this->_app->getStore()->getCode();
        if (Mage::getStoreConfig('web/url/use_store') && !empty($storeCode)) {
            $targetUrl = $this->_request->getBaseUrl() . '/' . $storeCode . '/' . $this->_rewrite->getTargetPath();
        }

        $queryString = $this->_getQueryString();
        if ($queryString) {
            $targetUrl .= '?' . $queryString;
        }

        if ($this->_rewrite->hasOption('R') || $isPermanentRedirectOption) {
            $this->_sendRedirectHeaders($targetUrl, $isPermanentRedirectOption);
        }

        $this->_request->setRequestUri($targetUrl);
        $this->_request->setPathInfo($this->_rewrite->getTargetPath());

        return $this;
    }

    /**
     * Load rewrite model
     *
     * @return Enterprise_UrlRewrite_Model_Url_Rewrite_Request
     */
    protected function _loadRewrite()
    {
        $requestPath = $this->_getRequestPath();
        $requestPathWithGetParams = $this->_getRequestPathWithGetParams();

        if ($requestPathWithGetParams != $requestPath) {
            $this->_rewrite->loadByRequestPath(array('request' => $requestPathWithGetParams));
            if ($this->_rewrite->getId()) {
                $requestPath = $requestPathWithGetParams;
            }
        }

        if (!$this->_rewrite->getId()) {
            $paths = $this->_getSystemPaths($requestPath);
            if (count($paths)) {
                $this->_rewrite->loadByRequestPath($paths);
            }
        }

        if ($this->_rewrite->getId() && !$this->_rewrite->getIsSystem()) {
            /**
             * Try to load data by request path from redirect model
             */
            $this->_rewrite->setData(
                $this->_getRedirect($requestPath, $this->_rewrite->getStoreId())->getData()
            );
        }

        return $this;
    }

    /**
     * Returns RequestPath with GET params.
     *
     * @return string
     */
    protected function _getRequestPathWithGetParams()
    {
        $requestPath = $this->_getRequestPath();
        $requestUri = $this->_request->getRequestUri();
        if ($pos = strpos($requestUri, '?')) {
            $requestPath .= substr($requestUri, $pos);
        }
        return $requestPath;
    }

    /**
     * Get system path from request path
     *
     * @param string $requestPath
     * @return array
     */
    protected function _getSystemPaths($requestPath)
    {
        $parts = explode('/', $requestPath);
        $suffix = array_pop($parts);
        if (false !== strrpos($suffix, '.')) {
            $suffix = substr($suffix, 0, strrpos($suffix, '.'));
        }

        //don't process URLs, which may contain only prefix. Like /course.
        $wholePathWithoutPrefix = null;
        if (count($parts)) {
            $prefix = $parts[0];
            $storeId = Mage::app()->getStore()->getId();
            $processors = $this->_factory->getSingleton('tgc_catalog/urlrewrite_prefixProcessor_factory')
                ->getAllPrefixProcessors();
            foreach ($processors as $processor) {
                /* @var $processor Tgc_Catalog_Model_Urlrewrite_PrefixProcessor_Interface */
                if ($processor->match($prefix, $storeId)) {
                    $requestPath = substr($requestPath, strlen($prefix)+1);
                    $wholePathWithoutPrefix = trim(implode('/', array_slice($parts, 1)) . '/' . $suffix, '/');
                    break;
                }
            }
        }

        $paths = array(
            'request' => $requestPath,
            'suffix' => $suffix
        );
        if (count($parts)) {
            $paths['whole'] = implode('/', $parts) . '/' . $suffix;
        }
        if ($wholePathWithoutPrefix) {
            $paths['whole_no_prefix'] = $wholePathWithoutPrefix;
        }
        return $paths;
    }
}