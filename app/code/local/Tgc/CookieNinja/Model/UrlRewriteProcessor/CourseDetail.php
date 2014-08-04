<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_CookieNinja
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_CookieNinja_Model_UrlRewriteProcessor_CourseDetail
    extends Tgc_CookieNinja_Model_UrlRewriteProcessor_Abstract
    implements Tgc_CookieNinja_Model_UrlRewriteProcessor_Interface
{
    const COURSE_ID_WATCH_PARAM  = 'cid';
    const MESSAGE_ID_WATCH_PARAM = 'pid';

    /**
     * Checks, if UrlRewriteProcessor can rewrite the request.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     * @return bool
     */
    public function canProcess($request, $urlRewrite)
    {
        return $request->getPathInfo() == '/tgc/courses/course_detail.aspx';
    }

    /**
     * Processes request and changes URL rewrite object.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     */
    public function process($request, $urlRewrite)
    {
        if ($courseId = $request->getParam(self::COURSE_ID_WATCH_PARAM)) {
            //load product's URL.
            $helper = Mage::helper('tgc_catalog');
            $productUrl = $helper->getProductUrlFromCourseId($courseId, false);
            if ($productUrl) {
                $this->_setRewriteTargetPath($urlRewrite, $productUrl);
            }
        }
        if ($messageId = $request->getParam(self::MESSAGE_ID_WATCH_PARAM)) {
            $targetPath = $urlRewrite->getTargetPath();
            $targetPath .= '?pid='.urlencode($messageId);
            $this->_setRewriteTargetPath($urlRewrite, $targetPath);
        }
    }
}
