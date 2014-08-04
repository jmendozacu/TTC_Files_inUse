<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_CookieNinja
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_CookieNinja_Model_UrlRewriteProcessor_Factory
{
    protected $_processors = array(
        'professor_details' => 'ninja/urlRewriteProcessor_professorDetails',
        'course_detail' => 'ninja/urlRewriteProcessor_courseDetail',
        'podcast' => 'ninja/urlRewriteProcessor_podcast'
    );

    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @param Enterprise_UrlRewrite_Model_Url_Rewrite $urlRewrite
     * @return Tgc_CookieNinja_Model_UrlRewriteProcessor_Interface|bool
     */
    public function getUrlRewriteProcessor($request, $urlRewrite)
    {
        /* @var $processor Tgc_CookieNinja_Model_UrlRewriteProcessor_Interface */
        foreach ($this->_getProcessors() as $processorModel) {
            $processor = Mage::getModel($processorModel);
            if ($processor->canProcess($request, $urlRewrite)) {
                return $processor;
            }
        }
        return false;
    }

    protected function _getProcessors()
    {
        return $this->_processors;
    }
}
