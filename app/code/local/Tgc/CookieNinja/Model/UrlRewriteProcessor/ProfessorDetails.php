<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_CookieNinja
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_CookieNinja_Model_UrlRewriteProcessor_ProfessorDetails
    extends Tgc_CookieNinja_Model_UrlRewriteProcessor_Abstract
    implements Tgc_CookieNinja_Model_UrlRewriteProcessor_Interface
{
    const PROFESSOR_WATCH_PARAM  = 'pid';

    /**
     * Checks, if UrlRewriteProcessor can rewrite the request.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     * @return bool
     */
    public function canProcess($request, $urlRewrite)
    {
        return !strcasecmp($request->getPathInfo(), '/tgc/professors/professor_detail.aspx');
    }

    /**
     * Processes request and changes URL rewrite object.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     */
    public function process($request, $urlRewrite)
    {
        if ($professorId = $request->getParam(self::PROFESSOR_WATCH_PARAM)) {
            $targetPath = $urlRewrite->getTargetPath();
            $targetPath .= '/id/'.urlencode($professorId).'/';
            $this->_setRewriteTargetPath($urlRewrite, $targetPath);
        }
    }
}
