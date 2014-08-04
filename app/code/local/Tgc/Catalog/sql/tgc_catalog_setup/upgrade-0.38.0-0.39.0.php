<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

$attributesUpdate = array(
  'special_from_date',
  'news_from_date',
  'custom_design_from',
);

foreach($attributesUpdate as $attributeUpdate) {
    $this->updateAttribute(4, $attributeUpdate,'backend_model','tgc_catalog/product_attribute_backend_startdate');
}