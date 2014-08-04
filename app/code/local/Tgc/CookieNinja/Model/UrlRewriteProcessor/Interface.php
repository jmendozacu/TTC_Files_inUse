<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_CookieNinja
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
interface Tgc_CookieNinja_Model_UrlRewriteProcessor_Interface
{
    /**
     * Checks, if UrlRewriteProcessor can rewrite the request.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     * @return bool
     */
    public function canProcess($request, $urlRewrite);

    /**
     * Processes request and changes URL rewrite object.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     */
    public function process($request, $urlRewrite);
}
