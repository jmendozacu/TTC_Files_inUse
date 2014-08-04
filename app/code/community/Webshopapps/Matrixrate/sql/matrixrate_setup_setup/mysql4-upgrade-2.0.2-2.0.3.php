<?php
/**
 * Shipping Table Rates
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Webshopapps
 * @package     Matrixrate
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Webshopapps_Matrixrate_Model_Setup */

$installer = $this;


/* Set variables used in queries */
$shipping_rate_condition = "package_value"; // Price from - to
$website_us = "US"; // ISO3 Country Code
$website_uk = "GB"; // ISO3 Country Code
$website_au = "AU"; // ISO3 Country Code
$standard_shipping = "Ground Delivery";
$two_day_shipping = "2nd Day Express";
$overnight_shipping = "Overnight Express";
$two_day_price_markup = 5.0000;
$overnight_price_markup = 10.0000;


$installer->startSetup();


/* Insert US Site Table Rates */
$installer->run("

INSERT INTO {$this->getTable('shipping_matrixrate')} (
  website_id,
  dest_country_id,
  dest_region_id,
  condition_name,
  condition_from_value,
  condition_to_value,
  price,
  cost,
  delivery_type
)

VALUES

(1,	'$website_us',	0, '$shipping_rate_condition', 0.0000, 79.9900,	10.0000, 0.0000, '$standard_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 80.0000,	119.9900, 15.0000, 0.0000, '$standard_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 120.0000,	159.9900, 20.0000, 0.0000, '$standard_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 160.0000,	99999.0000, 25.0000, 0.0000, '$standard_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 0.0000, 79.9900,	10.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 80.0000,	119.9900, 15.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 120.0000,	159.9900, 20.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 160.0000,	99999.0000, 25.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 0.0000, 79.9900,	10.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 80.0000,	119.9900, 15.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 120.0000,	159.9900, 20.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(1,	'$website_us',	0, '$shipping_rate_condition', 160.0000,	99999.0000, 25.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping')


   ");


/* Insert UK Site Table Rates */
$installer->run("

INSERT INTO {$this->getTable('shipping_matrixrate')} (
  website_id,
  dest_country_id,
  dest_region_id,
  condition_name,
  condition_from_value,
  condition_to_value,
  price,
  cost,
  delivery_type
)

VALUES

(2,	'$website_uk',	0, '$shipping_rate_condition', 0.0000, 49.9900,	4.9900, 0.0000, '$standard_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 50.0000, 84.9900,	8.9900, 0.0000, '$standard_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 85.0000, 119.9900, 11.9900, 0.0000, '$standard_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 120.0000, 199.9900, 14.9900, 0.0000, '$standard_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 200.0000, 99999.0000,	19.9900, 0.0000, '$standard_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 0.0000, 49.9900,	4.9900+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 50.0000, 84.9900,	8.9900+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 85.0000, 119.9900, 11.9900+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 120.0000, 199.9900, 14.9900+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 200.0000, 99999.0000,	19.9900+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 0.0000, 49.9900,	4.9900+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 50.0000, 84.9900,	8.9900+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 85.0000, 119.9900, 11.9900+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 120.0000, 199.9900, 14.9900+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(2,	'$website_uk',	0, '$shipping_rate_condition', 200.0000, 99999.0000,	19.9900+'$overnight_price_markup', 0.0000, '$overnight_shipping')


   ");


/* Insert AUS Site Table Rates */
$installer->run("

INSERT INTO {$this->getTable('shipping_matrixrate')} (
  website_id,
  dest_country_id,
  dest_region_id,
  condition_name,
  condition_from_value,
  condition_to_value,
  price,
  cost,
  delivery_type
)

VALUES

(3,	'$website_au',	0, '$shipping_rate_condition', 0.0000, 84.9900,	12.0000, 0.0000, '$standard_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 85.0000, 119.9900, 18.0000, 0.0000, '$standard_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 120.0000, 174.9900,	24.0000, 0.0000, '$standard_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 175.0000, 229.9900,	30.0000, 0.0000, '$standard_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 230.0000, 99999.0000,	35.0000, 0.0000, '$standard_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 0.0000, 84.9900,	12.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 85.0000, 119.9900, 18.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 120.0000, 174.9900,	24.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 175.0000, 229.9900,	30.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 230.0000, 99999.0000,	35.0000+'$two_day_price_markup', 0.0000, '$two_day_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 0.0000, 84.9900,	12.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 85.0000, 119.9900, 18.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 120.0000, 174.9900,	24.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 175.0000, 229.9900,	30.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping'),
(3,	'$website_au',	0, '$shipping_rate_condition', 230.0000, 99999.0000,	35.0000+'$overnight_price_markup', 0.0000, '$overnight_shipping')



   ");

$installer->endSetup();


