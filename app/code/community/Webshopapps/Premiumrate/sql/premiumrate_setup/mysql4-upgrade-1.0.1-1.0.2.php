<?php
/**
 * Shipping Table Rates
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Webshopapps
 * @package     Premiumrate
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

$installer = $this;


/* Set variables used in queries */
$shipping_rate_condition = "package_standard"; // Standard includes price, weight, and item number conditions
$website_us_id = 1;
$website_uk_id = 2;
$website_au_id = 3;
$weight_from_value = $item_from_value = 0.0000;
$weight_to_value = $item_to_value = 10000000.0000;
$standard_shipping = "Ground Delivery";
$two_day_shipping = "2nd Day Express";
$overnight_shipping = "Overnight Express";
$two_day_price_markup = 5.0000;
$overnight_price_markup = 10.0000;


$installer->startSetup();

/* Enable shipping method */
$installer->setConfigData('carriers/premiumrate/active', 1);

/* Insert US Site Table Rates */
$installer->run("

INSERT INTO {$this->getTable('shipping_premiumrate')} (
  website_id,
  dest_country_id,
  dest_region_id,
  condition_name,
  weight_from_value,
  weight_to_value,
  item_from_value,
  item_to_value,
  price_from_value,
  price_to_value,
  price,
  cost,
  delivery_type,
  sort_order
)

VALUES

($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 79.9900,	10.0000, 0.0000, '$standard_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 80.0000,	119.9900, 15.0000, 0.0000, '$standard_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000,	159.9900, 20.0000, 0.0000, '$standard_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 160.0000,	99999.0000, 25.0000, 0.0000, '$standard_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 79.9900,	15.0000, 0.0000, '$two_day_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 80.0000,	119.9900, 20.0000, 0.0000, '$two_day_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000,	159.9900, 25.0000, 0.0000, '$two_day_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 160.0000,	99999.0000, 30.0000, 0.0000, '$two_day_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 79.9900,	20.0000, 0.0000, '$overnight_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 80.0000,	119.9900, 25.0000, 0.0000, '$overnight_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000,	159.9900, 30.0000, 0.0000, '$overnight_shipping', 0),
($website_us_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 160.0000,	99999.0000, 35.0000, 0.0000, '$overnight_shipping', 0)


");


/* Insert UK Site Table Rates */
$installer->run("

INSERT INTO {$this->getTable('shipping_premiumrate')} (
  website_id,
  dest_country_id,
  dest_region_id,
  condition_name,
  weight_from_value,
  weight_to_value,
  item_from_value,
  item_to_value,
  price_from_value,
  price_to_value,
  price,
  cost,
  delivery_type,
  sort_order
)

VALUES

($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 49.9900,	4.9900, 0.0000, '$standard_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 50.0000, 84.9900,	8.9900, 0.0000, '$standard_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 85.0000, 119.9900, 11.9900, 0.0000, '$standard_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000, 199.9900, 14.9900, 0.0000, '$standard_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 200.0000, 99999.0000,	19.9900, 0.0000, '$standard_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 49.9900,	9.9900, 0.0000, '$two_day_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 50.0000, 84.9900,	13.9900, 0.0000, '$two_day_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 85.0000, 119.9900, 16.9900, 0.0000, '$two_day_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000, 199.9900, 19.9900, 0.0000, '$two_day_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 200.0000, 99999.0000,	24.9900, 0.0000, '$two_day_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 49.9900,	14.9900, 0.0000, '$overnight_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 50.0000, 84.9900,	18.9900, 0.0000, '$overnight_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 85.0000, 119.9900, 21.9900, 0.0000, '$overnight_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000, 199.9900, 24.9900, 0.0000, '$overnight_shipping', 0),
($website_uk_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 200.0000, 99999.0000,	29.9900, 0.0000, '$overnight_shipping', 0)


");


/* Insert AUS Site Table Rates */
$installer->run("

INSERT INTO {$this->getTable('shipping_premiumrate')} (
  website_id,
  dest_country_id,
  dest_region_id,
  condition_name,
  weight_from_value,
  weight_to_value,
  item_from_value,
  item_to_value,
  price_from_value,
  price_to_value,
  price,
  cost,
  delivery_type,
  sort_order
)

VALUES

($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 84.9900,	12.0000, 0.0000, '$standard_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 85.0000, 119.9900, 18.0000, 0.0000, '$standard_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000, 174.9900,	24.0000, 0.0000, '$standard_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 175.0000, 229.9900,	30.0000, 0.0000, '$standard_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 230.0000, 99999.0000,	35.0000, 0.0000, '$standard_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 84.9900,	17.0000, 0.0000, '$two_day_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 85.0000, 119.9900, 23.0000, 0.0000, '$two_day_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000, 174.9900,	29.0000, 0.0000, '$two_day_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 175.0000, 229.9900,	35.0000, 0.0000, '$two_day_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 230.0000, 99999.0000,	40.0000, 0.0000, '$two_day_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 0.0000, 84.9900,	22.0000, 0.0000, '$overnight_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 85.0000, 119.9900, 28.0000, 0.0000, '$overnight_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 120.0000, 174.9900,	34.0000, 0.0000, '$overnight_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 175.0000, 229.9900,	40.0000, 0.0000, '$overnight_shipping', 0),
($website_au_id, '0', 0, '$shipping_rate_condition', $weight_from_value, $weight_to_value, $item_from_value, $item_to_value, 230.0000, 99999.0000,	45.0000, 0.0000, '$overnight_shipping', 0)


");


$installer->endSetup();