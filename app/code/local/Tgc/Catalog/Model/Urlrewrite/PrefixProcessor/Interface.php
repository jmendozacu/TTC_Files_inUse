<?php
/**
 * Interface for using implementing own Prefix Processor.
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_Catalog
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
interface Tgc_Catalog_Model_Urlrewrite_PrefixProcessor_Interface
{
    /**
     * Checks, if processor can cut the URL prefix.
     *
     * @param string $prefix
     * @param int $storeId
     * @return bool
     */
    public function match($prefix, $storeId);
}