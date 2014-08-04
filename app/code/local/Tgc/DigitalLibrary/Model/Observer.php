<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Model_Observer
{
    const TRANSCRIPT_BOOK     = 'Transcript Book';
    const DIGITAL_TRANSCRIPT  = 'Digital Transcript';
    const DVD                 = 'DVD';
    const CD                  = 'CD';
    const VIDEO_DOWNLOAD      = 'Video Download';
    const AUDIO_DOWNLOAD      = 'Audio Download';
    const CD_SOUNDTRACK       = 'CD Soundtrack';
    const SOUNDTRACK_DOWNLOAD = 'Soundtrack Download';
    const TRANSCRIPT_OPTION   = 'Include Digital Transcript';
    const MEDIA_FORMAT_LABEL  = 'Media Format';
    const ORDER_ID            = 'order_id';

    private $_videoFormats = array(self::DVD, self::VIDEO_DOWNLOAD);
    private $_audioFormats = array(self::CD, self::AUDIO_DOWNLOAD, self::CD_SOUNDTRACK, self::SOUNDTRACK_DOWNLOAD);
    private $_downloadableFormats = array(self::VIDEO_DOWNLOAD, self::AUDIO_DOWNLOAD, self::SOUNDTRACK_DOWNLOAD);
    private $_transcriptFormats = array(self::TRANSCRIPT_BOOK, self::DIGITAL_TRANSCRIPT);
    private $_formatsEachCourseId;

    public function addMediaAccessRights($observer)
    {
        if (Mage::registry('access_rights_added')) {
            return;
        }

        $order = $observer->getOrder();
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        if (!$customer->getId()) {
            return;
        }
        $this->_updateCustomerStatus($customer);
        $webUserId = $customer->getWebUserId();
        if (!$webUserId) {
            return;
        }
        $date = date(
            Varien_Date::DATE_PHP_FORMAT,
            Mage::getModel('core/date')->timestamp(strtotime(now()))
        );
        $items = $order->getItemsCollection();
        $insert = array();
        $courseCounter = 0;
        foreach ($items as $item) {
            $product = $item->getProduct();

            $streamingAvailability = $product->getAvailabilityOfStreaming();
            if (is_null($streamingAvailability)) {
                $streamingAvailability = 2;
            }

            if ($product->getTypeId() == 'configurable') {
                continue;
            }

            if ($this->_isDigitalTranscript($product)) {
                $this->_addDigitalTranscriptAccessRight($product, $webUserId);
            }

            if($item->getIsTranscriptProduct()) {
                continue;
            }

            if ($product->getId()) {
                $attributeSet = $this->_getAttributeSet($product);
                if($parentItemId = $item->getParentItemId()) {
                    $parentItem = $this->_getOrderItemByItemId($items, $parentItemId);
                    if($parentItem && $parentItem->getId()) {
                        if($parentProduct = $parentItem->getProduct()) {
                            $parentProductId  = $parentProduct->getId();
                        }
                    }
                }
            }

            if(!isset($parentProductId)) { //a configurable product whether it is a set or a course, should always have a parent id.
                continue;
            }

            switch ($attributeSet) {
                case 'Courses':
                    $parentIds = Mage::getModel('catalog/product_type_configurable')
                        ->getParentIdsByChild($product->getId());
                    if (!in_array($parentProductId, $parentIds)) {
                        continue;
                    }
                    if (!$product->getMediaFormat()) {
                        continue;
                    }
                    $mediaAttribute = $product->getResource()
                        ->getAttribute('media_format')
                        ->getFrontend()
                        ->getValue($product);
                    $format = $this->_getFormat($mediaAttribute);
                    $canStream = in_array($streamingAvailability, array($format, Tgc_DigitalLibrary_Model_Source_Streaming::BOTH));
                    //we need to add access rights when they can stream or download
                    if ($canStream || in_array($mediaAttribute, $this->_downloadableFormats)) {
                        //if user already has another item in cart belonging to same configurable product, and both products are either audio or video, then if clause
                        //another row should not be added to access rights tables.
                        if(!$this->_hasAccessRightAlreadyAddedForProduct($parentProductId, $format)) {
                            if ($format !== false) {
                                $insert[$courseCounter]['format'] = $format;
                            }
                            $download = $this->_getIsDownloadable($mediaAttribute);
                            if ($download !== false) {
                                $insert[$courseCounter]['is_downloadable'] = $download;
                            }

                            $insert[$courseCounter]['digital_transcript_purchased'] = $this->_getDigitalTranscriptPurchased($items, $item->getQuoteItemId(), $download);
                            $insert[$courseCounter]['web_user_id'] = $webUserId;
                            $insert[$courseCounter]['date_purchased'] = $date;
                            $insert[$courseCounter]['parent_product_id'] = $parentProductId; //it is called course_id in database, but field really holds parent product id.
                            $courseCounter++;
                        }
                    }
                break;
                case 'Sets':
                    $courses = $product->getSetMembers();
                    $coursesArray = explode(',', $courses);
                    foreach ($coursesArray as $courseId) {
                        $parentId = $this->_getProductIdFromCourseId($courseId);
                        if (!$parentId || !$product->getMediaFormat()) {
                            continue;
                        }
                        $mediaAttribute = $product->getResource()
                            ->getAttribute('media_format')
                            ->getFrontend()
                            ->getValue($product);
                        $format = $this->_getFormat($mediaAttribute);
                        $canStream = in_array($streamingAvailability, array($format, Tgc_DigitalLibrary_Model_Source_Streaming::BOTH));
                        // we need to add access rights when they can stream or download
                        if ($canStream || in_array($mediaAttribute, $this->_downloadableFormats)) {
                            //if user already has another item in cart belonging to same configurable product, and both products are either audio or video, then if clause
                            //another row should not be added to access rights tables.
                            if(!$this->_hasAccessRightAlreadyAddedForProduct($parentId, $format)) {
                                if ($format !== false) {
                                    $insert[$courseCounter]['format'] = $format;
                                }
                                $download = $this->_getIsDownloadable($mediaAttribute);
                                if ($download !== false) {
                                    $insert[$courseCounter]['is_downloadable'] = $download;
                                }
                                //Please note: digital transcripts cannot be purchased for sets.

                                $insert[$courseCounter]['web_user_id'] = $webUserId;
                                $insert[$courseCounter]['date_purchased'] = $date;
                                $insert[$courseCounter]['parent_product_id'] = $parentId; //it is called course_id in database, but field really holds parent product id.
                            }
                        }
                        $courseCounter++;
                    }
                    break;
            }
        }

        foreach ($insert as $accessRight) {
            try {
                $productIdOfParent = isset($accessRight['parent_product_id']) ? $accessRight['parent_product_id'] : null;
                $this->_addAccessRight($this->_map($productIdOfParent, $accessRight, $order));
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        Mage::register('access_rights_added', true, true);
    }

    protected function _addDigitalTranscriptAccessRight(Mage_Catalog_Model_Product $product, $webUserId)
    {
        $resource = Mage::getResourceModel('tgc_dl/accessRights');
        $resource->addDigitalTranscriptAccessRight($product->getCourseId(), $webUserId);
    }

    protected function _isDigitalTranscript(Mage_Catalog_Model_Product $product)
    {
        return $product->getAttributeText('media_format') == self::DIGITAL_TRANSCRIPT;
    }

    /**
     * Function accepts item id and returns the item.
     *
     * @param string $items
     * @param $itemId
     * @return bool
     */
    private function _getOrderItemByItemId($items = '', $itemId)
    {
        if($items instanceof Varien_Data_Collection) {
            foreach($items as $item) {
                if($item->getId() == $itemId) {
                    return $item;
                }
            }
        }

        return false;
    }

    private function _getProductIdFromCourseId($courseId)
    {
        $productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter('course_id', array('eq' => $courseId))
            ->addAttributeToFilter('type_id', array('eq' => 'configurable'));

        if($productCollection->count() > 0) {
            $productId = $productCollection->getFirstItem()->getId();
        } else {
            $productId = false;
        }

        return $productId;
    }

    private function _getAttributeSet(Mage_Catalog_Model_Product $product)
    {
        $attributeSetModel = Mage::getModel("eav/entity_attribute_set");
        $attributeSetModel->load($product->getAttributeSetId());
        $attributeSetName  = $attributeSetModel->getAttributeSetName();

        return $attributeSetName;
    }

    private function _map($courseId, array $accessRight, Mage_Sales_Model_Order $order)
    {
        if (empty($courseId)) {
            $message = Mage::helper('tgc_dl')->__(
                'Course ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        if (empty($accessRight['web_user_id'])) {
            $message = Mage::helper('tgc_dl')->__(
                'Web User ID column cannot be empty'
            );
            throw new InvalidArgumentException($message);
        }
        if (is_null($accessRight['format'])) {
            $message = Mage::helper('tgc_dl')->__(
                'No format has been specified'
            );
            throw new InvalidArgumentException($message);
        }

        $resource = Mage::getResourceModel('tgc_dl/accessRights');
        $data = array(
            'course_id'   => $courseId,
            'web_user_id' => $accessRight['web_user_id'],
            'format'      => $accessRight['format'],
        );
        $importClass = 'Tgc_DigitalLibrary_Model_Import_Entity_AccessRights';

        return array(
            $importClass::ENTITY_ID          => $resource->getAccessRightEntityId($data),
            $importClass::COURSE_ID          => $courseId,
            $importClass::FORMAT             => $accessRight['format'],
            $importClass::WEB_USER_ID        => $accessRight['web_user_id'],
            $importClass::IS_DOWNLOADABLE    => is_null($accessRight['is_downloadable']) ? 0 : $accessRight['is_downloadable'],
            $importClass::DIGITAL_TRANSCRIPT => isset($accessRight['digital_transcript_purchased']) ? intval($accessRight['digital_transcript_purchased']) : 0,
            $importClass::DATE_PURCHASED     => $accessRight['date_purchased'],
            self::ORDER_ID                   => $order->getIncrementId(),
        );
    }

    private function _hasAccessRightAlreadyAddedForProduct($parentProductId, $format)
    {
        $hasAccessRightAlreadyBeenAddedForProduct = false;

        if(isset($this->_formatsEachCourseId[$parentProductId][$format]) && $this->_formatsEachCourseId[$parentProductId][$format] == true) {
            $hasAccessRightAlreadyBeenAddedForProduct = true;
        } else {
            $this->_formatsEachCourseId[$parentProductId][$format] = true;
        }

        return $hasAccessRightAlreadyBeenAddedForProduct;
    }

    private function _addAccessRight(array $accessRight)
    {
        $resource = Mage::getResourceModel('tgc_dl/accessRights');
        $resource->addAccessRight($accessRight);
    }

    /**
     * Determines if an item that has been ordered has a downloadable (aka digital) transcript item associated with it.
     * If yes, functions returns true.
     *
     * @param $orderItems
     * @param $currentProductQuoteItemId
     * @param $isDownloadable
     * @return bool
     */
    private function _getDigitalTranscriptPurchased($orderItems, $currentProductQuoteItemId, $isDownloadable)
    {
        $isDigitalTranscriptPurchased = false;

        if($isDownloadable) {
            $quote = Mage::helper('checkout/cart')->getCart()->getQuote();
            foreach($orderItems as $orderItem) {
                if($orderItem->getIsTranscriptProduct()) { //ensures that we only loop through transcript products.
                    $orderItemQuoteItem = $quote->getItemById($orderItem->getQuoteItemId());
                    $currentProductQuoteItem = $quote->getItemById($currentProductQuoteItemId);

                    /* The column transcript_parent_item_id is really referring to the item_id in the quote table!!
                    The addMediaAccessRights function is working with the order item object, which does not have access to the quote item.
                    Therefore, the parent ids MUST be compared using the quote object, which this function does.*/
                    if($orderItemQuoteItem->getTranscriptParentItemId() == $currentProductQuoteItem->getParentItemId()) { //finds transcript product, matches current product, if it exists.
                            $isDigitalTranscriptPurchased = true;
                    }
                }
            }
        }

        return $isDigitalTranscriptPurchased;
    }

    private function _getIsDownloadable($mediaAttribute)
    {
        if (in_array($mediaAttribute, $this->_transcriptFormats)) {
            return false;
        }

        if (in_array($mediaAttribute, $this->_downloadableFormats)) {
            return '1';
        } else {
            return '0';
        }
    }

    private function _getFormat($mediaAttribute)
    {
        if (in_array($mediaAttribute, $this->_transcriptFormats)) {
            return false;
        }

        if (in_array($mediaAttribute, $this->_videoFormats)) {
            return Tgc_DigitalLibrary_Model_Source_Format::VIDEO;
        } else if (in_array($mediaAttribute, $this->_audioFormats)) {
            return Tgc_DigitalLibrary_Model_Source_Format::AUDIO;
        }

        return false;
    }

    private function _updateCustomerStatus(Mage_Customer_Model_Customer $customer)
    {
        if ($customer->getIsProspect()) {
            $customer->setIsProspect(0);
            $customer->save();
        }
    }
}
