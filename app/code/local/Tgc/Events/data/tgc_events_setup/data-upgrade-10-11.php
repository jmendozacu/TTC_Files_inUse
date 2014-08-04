<?php
/**
 * User: mhidalgo
 * Date: 08/05/14
 * Time: 11:30
 */
/** @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$collection = Mage::getModel('events/events')->getCollection();

foreach ($collection as $event) {
    $eventUrlPrefix = $event->getEventUrlPrefix();
    $newEventUrlPrefix = preg_replace('/\s+/', '-', strip_tags($eventUrlPrefix));

    if ($eventUrlPrefix != $newEventUrlPrefix) {
        $selectStores = $this->getConnection()->select()
            ->from($this->getTable('events/events_store'))
            ->where('event_id = (?)', $event->getId());

        $storesData = $this->getConnection()->fetchAll($selectStores); // echo '<pre>'; print_r($storesData);exit;
        if ($storesData)
        {
            $storeIds = array();
            foreach ($storesData as $_row)
            {
                $storeIds[] = $_row['store_id'];
            }

            $event->setData('stores', $storeIds);
        }

        $selectProduct = $this->getConnection()->select()
            ->from($this->getTable('events/events_product'))
            ->where('eventid = (?)', $event->getId());

        $productData = $this->getConnection()->fetchAll($selectProduct); // echo '<pre>'; print_r($storesData);exit;
        if ($productData)
        {
            $productIds = array('related' => "");
            foreach ($productData as $_row)
            {
                $productIds['related'] .= "&".$_row['product_id'];
            }

            $event->setData('links', $productIds);
        }
        $event->setEventUrlPrefix($newEventUrlPrefix);
        $event->save();
    }
}

$installer->endSetup();
