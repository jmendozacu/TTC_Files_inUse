<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$attributeCodes = array(
  'news_from_date',
  'authenticated_bestsellers',
  'guest_bestsellers',
);

$data = array(
    'used_for_sort_by' => '1',
    'used_in_product_listing' => '1',
    'is_searchable' => '1',
);

foreach($attributeCodes as $code) {
    $this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $code, $data);
}
