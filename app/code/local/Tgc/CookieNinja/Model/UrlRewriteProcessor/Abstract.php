<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_CookieNinja
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_CookieNinja_Model_UrlRewriteProcessor_Abstract
{
    /**
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     * @param string $targetPath
     */
    protected function _setRewriteTargetPath($urlRewrite, $targetPath)
    {
        $urlRewrite->setTargetPath($targetPath);
        $urlRewrite->setIsSystem(true);
        $urlRewrite->setOptions('RP');
    }
}