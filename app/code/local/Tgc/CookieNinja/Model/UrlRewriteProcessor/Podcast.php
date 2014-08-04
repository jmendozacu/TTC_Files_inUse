<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_CookieNinja
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_CookieNinja_Model_UrlRewriteProcessor_Podcast
    extends Tgc_CookieNinja_Model_UrlRewriteProcessor_Abstract
    implements Tgc_CookieNinja_Model_UrlRewriteProcessor_Interface
{
    const PODCAST_WATCH_PARAM    = 'eid';

    /**
     * Checks, if UrlRewriteProcessor can rewrite the request.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     * @return bool
     */
    public function canProcess($request, $urlRewrite)
    {
        return $request->getPathInfo() == '/tgc/Courses/PodcastEpisode.aspx';
    }

    /**
     * Processes request and changes URL rewrite object.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     */
    public function process($request, $urlRewrite)
    {
        if ($podcastId = $request->getParam(self::PODCAST_WATCH_PARAM)) {
            $podcast = Mage::getModel('podcast/podcast')->load($podcastId, 'podcast_id');
            $identifier = Mage::helper('podcast')->encodeUrl($podcast->getTitle(), $podcast->getPodcastId());
            $targetPath = $urlRewrite->getTargetPath();
            $targetPath .= '/identifier/'.urlencode($identifier).'/';
            $this->_setRewriteTargetPath($urlRewrite, $targetPath);
        }
    }
}
