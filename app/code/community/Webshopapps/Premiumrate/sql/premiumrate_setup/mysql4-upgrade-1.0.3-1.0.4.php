<?php
/**
 * User: mhidalgo
 * Date: 19/03/14
 * Time: 12:46
 */
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$websites = Mage::getModel('core/website')->getCollection();

$weight_from_value = $item_from_value = 0.0000;
$weight_to_value = $item_to_value = 10000000.0000;
$standard_shipping = "Standard";
$two_day_shipping = "2nd Day Express";
$overnight_shipping = "Overnight Express";
$shipping_rate_condition = "package_standard";

$mexicoAndCanada = "CA,MX";

$europeAndFarEast = "KW,KZ,NP,AF,KG,PK,SG,LB,OM,IQ,MN,RU,IL,MY,IR,JP,ID,QA,IN,HK,PH,MV,LA,JO,SA,CN,AE,BT,LK,TM,BN,KH,TH,TJ,SY,TW,BD,KR,TR,BH,YE,VN,UZ,AT,FI,MC,MD,GE,RS,AZ,FO,SM,ME,GR,AL,NO,SK,AD,SI,NL,DE,FR,EE,AM,MT,ES,HU,LV,IS,CY,BG,HR,SE,BY,CH,IE,IT,CZ,RO,GB,BE,UA,MK,PL,LU,PT,LI,LT,DK";

$toImport = array();
$pricesUS = array();
$pricesUK = array();
$pricesAU = array();
$pricesInternational = array();

/////////////////////////////////////////////// USA SHIPPING RATES
$pricesUS[] = array(
    'price_from_value' => 0,
    'price_to_value' => 79.99,
    'price' => 10,
    'delivery_type' => $standard_shipping
);

$pricesUS[] = array(
    'price_from_value' => 80,
    'price_to_value' => 119.99,
    'price' => 15,
    'delivery_type' => $standard_shipping
);

$pricesUS[] = array(
    'price_from_value' => 120,
    'price_to_value' => 159.99,
    'price' => 20,
    'delivery_type' => $standard_shipping
);

$pricesUS[] = array(
    'price_from_value' => 160,
    'price_to_value' => 99999,
    'price' => 25,
    'delivery_type' => $standard_shipping
);

$pricesUS[] = array(
    'price_from_value' => 0,
    'price_to_value' => 79.99,
    'price' => 15,
    'delivery_type' => $two_day_shipping
);

$pricesUS[] = array(
    'price_from_value' => 80,
    'price_to_value' => 119.99,
    'price' => 20,
    'delivery_type' => $two_day_shipping
);

$pricesUS[] = array(
    'price_from_value' => 120,
    'price_to_value' => 159.99,
    'price' => 25,
    'delivery_type' => $two_day_shipping
);

$pricesUS[] = array(
    'price_from_value' => 160,
    'price_to_value' => 99999,
    'price' => 30,
    'delivery_type' => $two_day_shipping
);

$pricesUS[] = array(
    'price_from_value' => 0,
    'price_to_value' => 79.99,
    'price' => 20,
    'delivery_type' => $overnight_shipping
);

$pricesUS[] = array(
    'price_from_value' => 80,
    'price_to_value' => 119.99,
    'price' => 25,
    'delivery_type' => $overnight_shipping
);

$pricesUS[] = array(
    'price_from_value' => 120,
    'price_to_value' => 159.99,
    'price' => 30,
    'delivery_type' => $overnight_shipping
);

$pricesUS[] = array(
    'price_from_value' => 160,
    'price_to_value' => 99999,
    'price' => 35,
    'delivery_type' => $overnight_shipping
);

/////////////////////////////////////////////// UK SHIPPING RATES
$pricesUK[] = array(
    'price_from_value' => 0,
    'price_to_value' => 49.99,
    'price' => 4.99,
    'delivery_type' => $standard_shipping
);

$pricesUK[] = array(
    'price_from_value' => 50,
    'price_to_value' => 84.99,
    'price' => 8.99,
    'delivery_type' => $standard_shipping
);

$pricesUK[] = array(
    'price_from_value' => 85,
    'price_to_value' => 119.99,
    'price' => 11.99,
    'delivery_type' => $standard_shipping
);

$pricesUK[] = array(
    'price_from_value' => 120,
    'price_to_value' => 199.99,
    'price' => 14.99,
    'delivery_type' => $standard_shipping
);

$pricesUK[] = array(
    'price_from_value' => 200,
    'price_to_value' => 99999,
    'price' => 19.99,
    'delivery_type' => $standard_shipping
);

$pricesUK[] = array(
    'price_from_value' => 0,
    'price_to_value' => 49.99,
    'price' => 9.99,
    'delivery_type' => $two_day_shipping
);

$pricesUK[] = array(
    'price_from_value' => 50,
    'price_to_value' => 84.99,
    'price' => 13.99,
    'delivery_type' => $two_day_shipping
);

$pricesUK[] = array(
    'price_from_value' => 85,
    'price_to_value' => 119.99,
    'price' => 16.99,
    'delivery_type' => $two_day_shipping
);

$pricesUK[] = array(
    'price_from_value' => 120,
    'price_to_value' => 199.99,
    'price' => 19.99,
    'delivery_type' => $two_day_shipping
);

$pricesUK[] = array(
    'price_from_value' => 200,
    'price_to_value' => 99999,
    'price' => 24.99,
    'delivery_type' => $two_day_shipping
);

$pricesUK[] = array(
    'price_from_value' => 0,
    'price_to_value' => 49.99,
    'price' => 14.99,
    'delivery_type' => $overnight_shipping
);

$pricesUK[] = array(
    'price_from_value' => 50,
    'price_to_value' => 84.99,
    'price' => 18.99,
    'delivery_type' => $overnight_shipping
);

$pricesUK[] = array(
    'price_from_value' => 85,
    'price_to_value' => 119.99,
    'price' => 21.99,
    'delivery_type' => $overnight_shipping
);

$pricesUK[] = array(
    'price_from_value' => 120,
    'price_to_value' => 199.99,
    'price' => 24.99,
    'delivery_type' => $overnight_shipping
);

$pricesUK[] = array(
    'price_from_value' => 200,
    'price_to_value' => 99999,
    'price' => 29.99,
    'delivery_type' => $overnight_shipping
);

/////////////////////////////////////////////// AU SHIPPING RATES

$pricesAU[] = array(
    'price_from_value' => 0,
    'price_to_value' => 84.99,
    'price' => 12,
    'delivery_type' => $standard_shipping
);

$pricesAU[] = array(
    'price_from_value' => 85,
    'price_to_value' => 119.99,
    'price' => 18,
    'delivery_type' => $standard_shipping
);

$pricesAU[] = array(
    'price_from_value' => 120,
    'price_to_value' => 174.99,
    'price' => 24,
    'delivery_type' => $standard_shipping
);

$pricesAU[] = array(
    'price_from_value' => 175,
    'price_to_value' => 229.99,
    'price' => 30,
    'delivery_type' => $standard_shipping
);

$pricesAU[] = array(
    'price_from_value' => 230,
    'price_to_value' => 99999,
    'price' => 35,
    'delivery_type' => $standard_shipping
);

$pricesAU[] = array(
    'price_from_value' => 0,
    'price_to_value' => 84.99,
    'price' => 17,
    'delivery_type' => $two_day_shipping
);

$pricesAU[] = array(
    'price_from_value' => 85,
    'price_to_value' => 119.99,
    'price' => 23,
    'delivery_type' => $two_day_shipping
);

$pricesAU[] = array(
    'price_from_value' => 120,
    'price_to_value' => 174.99,
    'price' => 29,
    'delivery_type' => $two_day_shipping
);

$pricesAU[] = array(
    'price_from_value' => 175,
    'price_to_value' => 229.99,
    'price' => 35,
    'delivery_type' => $two_day_shipping
);

$pricesAU[] = array(
    'price_from_value' => 230,
    'price_to_value' => 99999,
    'price' => 40,
    'delivery_type' => $two_day_shipping
);

$pricesAU[] = array(
    'price_from_value' => 0,
    'price_to_value' => 84.99,
    'price' => 22,
    'delivery_type' => $overnight_shipping
);

$pricesAU[] = array(
    'price_from_value' => 85,
    'price_to_value' => 119.99,
    'price' => 28,
    'delivery_type' => $overnight_shipping
);

$pricesAU[] = array(
    'price_from_value' => 120,
    'price_to_value' => 174.99,
    'price' => 34,
    'delivery_type' => $overnight_shipping
);

$pricesAU[] = array(
    'price_from_value' => 175,
    'price_to_value' => 229.99,
    'price' => 40,
    'delivery_type' => $overnight_shipping
);

$pricesAU[] = array(
    'price_from_value' => 230,
    'price_to_value' => 99999,
    'price' => 45,
    'delivery_type' => $overnight_shipping
);

/////////////////////////////////////////////// INTERNATIONAL SHIPPING RATES

$internationalEurEastCountries = explode(',',$europeAndFarEast);

$internationalMexCanCountries = explode(',',$mexicoAndCanada);

$internationalOtherCountries = array('0');

foreach ($internationalEurEastCountries as $internationalEurEastCountry) {
    $pricesInternational[] = array(
        'dest_country_id' => $internationalEurEastCountry,
        'price_from_value' => 0,
        'price_to_value' => 99.99,
        'price' => 30,
        'delivery_type' => $standard_shipping
    );

    $pricesInternational[] = array(
        'dest_country_id' => $internationalEurEastCountry,
        'price_from_value' => 100,
        'price_to_value' => 299.99,
        'price' => 45,
        'delivery_type' => $standard_shipping
    );

    $pricesInternational[] = array(
        'dest_country_id' => $internationalEurEastCountry,
        'price_from_value' => 300,
        'price_to_value' => 99999,
        'price' => 65,
        'delivery_type' => $standard_shipping
    );
}

foreach ($internationalMexCanCountries as $internationalMexCanCountry) {
    $pricesInternational[] = array(
        'dest_country_id' => $internationalMexCanCountry,
        'price_from_value' => 0,
        'price_to_value' => 99.99,
        'price' => 25,
        'delivery_type' => $standard_shipping
    );

    $pricesInternational[] = array(
        'dest_country_id' => $internationalMexCanCountry,
        'price_from_value' => 100,
        'price_to_value' => 299.99,
        'price' => 35,
        'delivery_type' => $standard_shipping
    );

    $pricesInternational[] = array(
        'dest_country_id' => $internationalMexCanCountry,
        'price_from_value' => 300,
        'price_to_value' => 99999,
        'price' => 45,
        'delivery_type' => $standard_shipping
    );
}

foreach ($internationalOtherCountries as $internationalOtherCountry) {
    $pricesInternational[] = array(
        'dest_country_id' => $internationalOtherCountry,
        'price_from_value' => 0,
        'price_to_value' => 99.99,
        'price' => 40,
        'delivery_type' => $standard_shipping
    );

    $pricesInternational[] = array(
        'dest_country_id' => $internationalOtherCountry,
        'price_from_value' => 100,
        'price_to_value' => 299.99,
        'price' => 60,
        'delivery_type' => $standard_shipping
    );

    $pricesInternational[] = array(
        'dest_country_id' => $internationalOtherCountry,
        'price_from_value' => 300,
        'price_to_value' => 99999,
        'price' => 80,
        'delivery_type' => $standard_shipping
    );
}

/////////////////////// SQL BUILDER

$installer->run("
    TRUNCATE TABLE {$this->getTable('shipping_premiumrate')}
");

foreach ($websites as $website) {
    $sql = "";
    $websiteId = $website->getId();
    $toImport = array(
        'websiteId' => $websiteId,
        'dest_region_id' => 0,
        'condition_name' => $shipping_rate_condition,
        'weight_from_value' => $weight_from_value,
        'weight_to_value' => $weight_to_value,
        'item_from_value' => $item_from_value,
        'item_to_value' => $item_to_value,
        'cost' => 0.0000,
        'sort_order' => 0
    );
    switch ($website->getName()) {

        case "US":
            $country_code = "US";
            $toImport['dest_country_id'] = $country_code;

            foreach($pricesUS as $price) {
                $sql .= "(".
                    $toImport['websiteId'].
                    ", '".
                    $toImport['dest_country_id'].
                    "', ".
                    $toImport['dest_region_id'].
                    ", '".
                    $toImport['condition_name'].
                    "', ".
                    $toImport['weight_from_value'].
                    ", ".
                    $toImport['weight_to_value'].
                    ", ".
                    $toImport['item_from_value'].
                    ", ".
                    $toImport['item_to_value'].
                    ", ".
                    $price['price_from_value'].
                    ", ".
                    $price['price_to_value'].
                    ", ".
                    $price['price'].
                    ", ".
                    $toImport['cost'].
                    ", '".
                    $price['delivery_type'].
                    "', ".
                    $toImport['sort_order'].
                    "),";
            }
        break;
        case "UK":
            $country_code = "GB";
            $toImport['dest_country_id'] = $country_code;

            foreach($pricesUK as $price) {
                $sql .= "(".
                    $toImport['websiteId'].
                    ", '".
                    $toImport['dest_country_id'].
                    "', ".
                    $toImport['dest_region_id'].
                    ", '".
                    $toImport['condition_name'].
                    "', ".
                    $toImport['weight_from_value'].
                    ", ".
                    $toImport['weight_to_value'].
                    ", ".
                    $toImport['item_from_value'].
                    ", ".
                    $toImport['item_to_value'].
                    ", ".
                    $price['price_from_value'].
                    ", ".
                    $price['price_to_value'].
                    ", ".
                    $price['price'].
                    ", ".
                    $toImport['cost'].
                    ", '".
                    $price['delivery_type'].
                    "', ".
                    $toImport['sort_order'].
                    "),";
            }
        break;
        case "Australia":
            $country_code = "AU";
            $toImport['dest_country_id'] = $country_code;

            foreach($pricesAU as $price) {
                $sql .= "(".
                    $toImport['websiteId'].
                    ", '".
                    $toImport['dest_country_id'].
                    "', ".
                    $toImport['dest_region_id'].
                    ", '".
                    $toImport['condition_name'].
                    "', ".
                    $toImport['weight_from_value'].
                    ", ".
                    $toImport['weight_to_value'].
                    ", ".
                    $toImport['item_from_value'].
                    ", ".
                    $toImport['item_to_value'].
                    ", ".
                    $price['price_from_value'].
                    ", ".
                    $price['price_to_value'].
                    ", ".
                    $price['price'].
                    ", ".
                    $toImport['cost'].
                    ", '".
                    $price['delivery_type'].
                    "', ".
                    $toImport['sort_order'].
                    "),";
            }
        break;
    }

    foreach($pricesInternational as $price) {
        if ($website->getName() != "UK" && $price['dest_country_id'] != "GB") {
            $toImport['dest_country_id'] = $price['dest_country_id'];

            $sql .= "(".
                $toImport['websiteId'].
                ", '".
                $toImport['dest_country_id'].
                "', ".
                $toImport['dest_region_id'].
                ", '".
                $toImport['condition_name'].
                "', ".
                $toImport['weight_from_value'].
                ", ".
                $toImport['weight_to_value'].
                ", ".
                $toImport['item_from_value'].
                ", ".
                $toImport['item_to_value'].
                ", ".
                $price['price_from_value'].
                ", ".
                $price['price_to_value'].
                ", ".
                $price['price'].
                ", ".
                $toImport['cost'].
                ", '".
                $price['delivery_type'].
                "', ".
                $toImport['sort_order'].
                "),";
        }
    }
    //Delete last character ","
    $sql = substr($sql,0,-1);
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

        {$sql}
    ");
}
$installer->endSetup();