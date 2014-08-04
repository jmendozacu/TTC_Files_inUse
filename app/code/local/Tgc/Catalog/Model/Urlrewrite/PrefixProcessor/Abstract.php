<?php
/**
 * Base class for URL prefix processors
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
abstract class Tgc_Catalog_Model_Urlrewrite_PrefixProcessor_Abstract
    implements Tgc_Catalog_Model_Urlrewrite_PrefixProcessor_Interface
{
    /**
     * Collects URL prefixes
     *
     * @param int $storeId
     * @return array
     */
    abstract protected function _getPrefixes($storeId);

    /**
     * Checks, if processor can cut the URL prefix.
     *
     * @param string $prefix
     * @param int $storeId
     * @return bool
     */
    public function match($prefix, $storeId)
    {
        if (in_array($prefix, $this->_getPrefixes($storeId))) {
            return true;
        }
        return false;
    }
}