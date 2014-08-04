<?php
/**
 * Bazaarvoice review import
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Model_Convert_Adapter_Review
    extends Mage_Eav_Model_Convert_Adapter_Entity
{
    const ENTITY            = 'bv_review';
    const RATING_ATTRIBUTE  = 'average_rating';
    const ENTITY_TYPE       = 1;
    const INLINE_ATTRIBUTE  = 'inline_rating';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix          = 'bv_review_import';

    private $_connection;
    private $_adminStoreId;
    private $_ratingOptionArray;
    private $_storeTable             = 'core_store';
    private $_optionValueTable       = 'eav_attribute_option_value';

    public function __construct()
    {
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('write');
        $this->_initRatingOptionArray();
        $this->_initAdminStoreId();
    }

    private function _initRatingOptionArray()
    {
        $session = Mage::getSingleton('core/session');
        if ($optionArray = $session->getRatingOptionArray()) {
            $this->_ratingOptionArray = $optionArray;
        }
    }

    private function _initAdminStoreId()
    {
        $session = Mage::getSingleton('core/session');
        if ($storeId = $session->getAdminStoreId()) {
            $this->_adminStoreId = $storeId;
        }
    }

    public function saveRow(array $row)
    {
        $object = simplexml_load_string($row[0]);

        if ($this->_isRatingImport($object)) {
            $data = $this->_prepareRatingsData($object);
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $data['sku']);
            if ($product && $product->getId()) {
                $product->setInlineRating($data['avg_rating']);
                $product->setAverageRating($this->_getAttributeOptionValue($data));
                $product->save();
            }
        }

        return true;
    }

    private function _isRatingImport(SimpleXMLElement $object)
    {
        return isset($object->ReviewStatistics);
    }

    private function _prepareRatingsData(SimpleXMLElement $object)
    {
        $reviewStatistics = $object->ReviewStatistics;
        $externalId = (string)$object->ExternalId;
        $reviewData = array(
            'sku'            => $externalId,
            'avg_rating'     => (float)$reviewStatistics->AverageOverallRating,
        );

        return $reviewData;
    }

    private function _getRatingOptionArray()
    {
        if (isset($this->_ratingOptionArray)) {
            return $this->_ratingOptionArray;
        }

        $values  = $this->_getRatingOptionValues();
        $storeId = $this->_getAdminStoreId();
        $this->_ratingOptionArray = array();

        foreach ($values as $key => $value) {
            $select = $this->_connection->select()
                ->from($this->_optionValueTable, 'option_id')
                ->where('value = :value')
                ->where('store_id = :storeId');
            $bind = array(
                ':value' => (string)$value,
                ':storeId' => (int)$storeId,
            );
            $optionId = (int)$this->_connection->fetchOne($select, $bind);
            $this->_ratingOptionArray[$key] = $optionId;
        }

        $session = Mage::getSingleton('core/session');
        $session->setRatingOptionArray($this->_ratingOptionArray);

        return $this->_ratingOptionArray;
    }

    private function _getAdminStoreId()
    {
        if (isset($this->_adminStoreId)) {
            return $this->_adminStoreId;
        }

        $select = $this->_connection->select()
            ->from($this->_storeTable, 'store_id')
            ->where('code = :code');
        $bind = array(
            ':code' => (string)Mage_Core_Model_Store::ADMIN_CODE,
        );

        $this->_adminStoreId = (int)$this->_connection->fetchOne($select, $bind);

        $session = Mage::getSingleton('core/session');
        $session->setAdminStoreId($this->_adminStoreId);

        return $this->_adminStoreId;
    }

    private function _getRatingOptionValues()
    {
        return array(
            '3' => Tgc_Bazaarvoice_Helper_Data::OPTION_3_STAR,
            '4' => Tgc_Bazaarvoice_Helper_Data::OPTION_4_STAR,
            '5' => Tgc_Bazaarvoice_Helper_Data::OPTION_5_STAR,
        );
    }

    private function _getAttributeOptionValue(array $data)
    {
        $options  = $this->_getRatingOptionArray();
        $avgStars = $data['avg_rating'];
        $value    = array();

        for ($i = 3; $i <= 5; $i++) {
            if ($avgStars >= $i) {
                $value[] = $options[$i];
            }
        }

        if (empty($value)) {
            return false;
        }

        return join(',', $value);
    }
}
