<?php

/**
 * Product:       Xtento_ProductExport (1.2.10)
 * ID:            3WBIjQCn8HF0ygiBVtNwYZ72yG3r/EJw/pL2BiMF/UA=
 * Packaged:      2014-04-03T06:13:01+00:00
 * Last Modified: 2013-02-11T11:34:30+01:00
 * File:          app/code/local/Xtento/ProductExport/Model/System/Config/Source/Order/Status.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_ProductExport_Model_System_Config_Source_Order_Status
{
    public function toOptionArray()
    {
        $statuses[] = array('value' => '', 'label' => Mage::helper('adminhtml')->__('-- No change --'));

        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.5.0.0', '>=')) {
            # Support for Custom Order Status introduced in Magento version 1.5
            $orderStatus = Mage::getModel('sales/order_config')->getStatuses();
            foreach ($orderStatus as $status => $label) {
                $statuses[] = array('value' => $status, 'label' => Mage::helper('adminhtml')->__((string)$label));
            }
        } else {
            $orderStatus = Mage::getModel('adminhtml/system_config_source_order_status')->toOptionArray();
            foreach ($orderStatus as $status) {
                if ($status['value'] == '') {
                    continue;
                }
                $statuses[] = array('value' => $status['value'], 'label' => Mage::helper('adminhtml')->__((string)$status['label']));
            }
        }
        return $statuses;
    }

    // Function to just put all order status "codes" into an array.
    public function toArray()
    {
        $statuses = $this->toOptionArray();
        $statusArray = array();
        foreach ($statuses as $status) {
            $statusArray[$status['value']];
        }
        return $statusArray;
    }

    static function isEnabled()
    {
        return eval(call_user_func('ba' . 'se64_' . 'dec' . 'ode', "JGV4dElkID0gJ1h0ZW50b19Qcm9kdWN0RXhwb3J0OTkwOTkwJzsNCiRzUGF0aCA9ICdwcm9kdWN0ZXhwb3J0L2dlbmVyYWwvJzsNCiRzTmFtZSA9IE1hZ2U6OmdldE1vZGVsKCd4dGVudG9fcHJvZHVjdGV4cG9ydC9zeXN0ZW1fY29uZmlnX2JhY2tlbmRfZXhwb3J0X3NlcnZlcicpLT5nZXRGaXJzdE5hbWUoKTsNCiRzTmFtZTIgPSBNYWdlOjpnZXRNb2RlbCgneHRlbnRvX3Byb2R1Y3RleHBvcnQvc3lzdGVtX2NvbmZpZ19iYWNrZW5kX2V4cG9ydF9zZXJ2ZXInKS0+Z2V0U2Vjb25kTmFtZSgpOw0KJHMgPSB0cmltKE1hZ2U6OmdldE1vZGVsKCdjb3JlL2NvbmZpZ19kYXRhJyktPmxvYWQoJHNQYXRoIC4gJ3NlcmlhbCcsICdwYXRoJyktPmdldFZhbHVlKCkpOw0KaWYgKCgkcyAhPT0gc2hhMShzaGExKCRleHRJZCAuICdfJyAuICRzTmFtZSkpKSAmJiAkcyAhPT0gc2hhMShzaGExKCRleHRJZCAuICdfJyAuICRzTmFtZTIpKSkgew0KTWFnZTo6Z2V0Q29uZmlnKCktPnNhdmVDb25maWcoJHNQYXRoIC4gJ2VuYWJsZWQnLCAwKTsNCk1hZ2U6OmdldENvbmZpZygpLT5jbGVhbkNhY2hlKCk7DQpyZXR1cm4gZmFsc2U7DQp9IGVsc2Ugew0KcmV0dXJuIHRydWU7DQp9"));
    }
}
