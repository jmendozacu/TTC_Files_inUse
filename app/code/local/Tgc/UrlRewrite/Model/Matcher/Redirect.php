<?php
/**
 * Url Redirect matcher model.
 * Overridden, because we need to have case-insensitive URL redirects comparison.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_UrlRewrite
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_UrlRewrite_Model_Matcher_Redirect extends Enterprise_UrlRewrite_Model_Matcher_Redirect
{
    /**
     * Match redirect rewrite.
     * Case-insensitive comparison has been added.
     *
     * @param array $rewriteRow
     * @param string $requestPath
     * @return bool
     */
    public function match(array $rewriteRow, $requestPath)
    {
        if (Enterprise_UrlRewrite_Model_Redirect::URL_REWRITE_ENTITY_TYPE != $rewriteRow['entity_type']) {
            return false;
        }

        if ($rewriteRow['store_id'] != $this->_prevStoreId
            && $rewriteRow['store_id'] != Mage_Core_Model_App::ADMIN_STORE_ID)
        {
            return false;
        }

        //CUSTOM CODE
        //We need to compare 2 URL keys case-insensitively
        if (!strcasecmp($rewriteRow['request_path'], $requestPath)) {
        //CUSTOM CODE END
            $this->_checkStoreRedirect($rewriteRow['url_rewrite_id']);
            return true;
        }
        return false;
    }
}
