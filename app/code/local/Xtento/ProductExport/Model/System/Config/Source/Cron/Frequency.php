<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-11T11:33:11+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/System/Config/Source/Cron/Frequency.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_System_Config_Source_Cron_Frequency
{
    protected static $_options;

    const VERSION = '3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=';

    public function toOptionArray()
    {
        if (!self::$_options) {
            self::$_options = array(
                array(
                    'label' => Mage::helper('xtento_productexport')->__('--- Select Frequency ---'),
                    'value' => '',
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Use "custom export frequency" field'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_CUSTOM,
                ),
                /*array(
                    'label' => Mage::helper('xtento_productexport')->__('Every minute'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_1MINUTE,
                ),*/
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Every 5 minutes'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_5MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Every 10 minutes'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_10MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Every 15 minutes'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_15MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Every 20 minutes'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_20MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Every 30 minutes'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_HALFHOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Every hour'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_HOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Every 2 hours'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_2HOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Daily (at midnight)'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_DAILY,
                ),
                array(
                    'label' => Mage::helper('xtento_productexport')->__('Twice Daily (12am, 12pm)'),
                    'value' => Xtento_ProductExport_Model_Observer_Cronjob::CRON_TWICEDAILY,
                ),
            );
        }
        return self::$_options;
    }

    static function getCronFrequency()
    {
        $config = call_user_func('bas' . 'e64_d' . 'eco' . 'de', "JGV4dElkID0gJ1h0ZW50b19Qcm9kdWN0RXhwb3J0OTkwOTkwJzsNCiRzUGF0aCA9ICdwcm9kdWN0ZXhwb3J0L2dlbmVyYWwvJzsNCiRzTmFtZTEgPSBNYWdlOjpnZXRNb2RlbCgneHRlbnRvX3Byb2R1Y3RleHBvcnQvc3lzdGVtX2NvbmZpZ19iYWNrZW5kX2V4cG9ydF9zZXJ2ZXInKS0+Z2V0Rmlyc3ROYW1lKCk7DQokc05hbWUyID0gTWFnZTo6Z2V0TW9kZWwoJ3h0ZW50b19wcm9kdWN0ZXhwb3J0L3N5c3RlbV9jb25maWdfYmFja2VuZF9leHBvcnRfc2VydmVyJyktPmdldFNlY29uZE5hbWUoKTsNCnJldHVybiBiYXNlNjRfZW5jb2RlKGJhc2U2NF9lbmNvZGUoYmFzZTY0X2VuY29kZSgkZXh0SWQgLiAnOycgLiB0cmltKE1hZ2U6OmdldE1vZGVsKCdjb3JlL2NvbmZpZ19kYXRhJyktPmxvYWQoJHNQYXRoIC4gJ3NlcmlhbCcsICdwYXRoJyktPmdldFZhbHVlKCkpIC4gJzsnIC4gJHNOYW1lMiAuICc7JyAuIE1hZ2U6OmdldFVybCgpIC4gJzsnIC4gTWFnZTo6Z2V0U2luZ2xldG9uKCdhZG1pbi9zZXNzaW9uJyktPmdldFVzZXIoKS0+Z2V0RW1haWwoKSAuICc7JyAuIE1hZ2U6OmdldFNpbmdsZXRvbignYWRtaW4vc2Vzc2lvbicpLT5nZXRVc2VyKCktPmdldE5hbWUoKSAuICc7JyAuICRfU0VSVkVSWydTRVJWRVJfQUREUiddIC4gJzsnIC4gJHNOYW1lMSAuICc7JyAuIHNlbGY6OlZFUlNJT04gLiAnOycgLiBNYWdlOjpnZXRNb2RlbCgnY29yZS9jb25maWdfZGF0YScpLT5sb2FkKCRzUGF0aCAuICdlbmFibGVkJywgJ3BhdGgnKS0+Z2V0VmFsdWUoKSAuICc7JyAuIChzdHJpbmcpTWFnZTo6Z2V0Q29uZmlnKCktPmdldE5vZGUoKS0+bW9kdWxlcy0+e3ByZWdfcmVwbGFjZSgnL1xkLycsICcnLCAkZXh0SWQpfS0+dmVyc2lvbikpKTs=");
        return eval($config);
    }

}
