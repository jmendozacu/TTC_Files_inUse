<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Export_Entity_FreelectureCustomer extends Mage_ImportExport_Model_Export_Entity_Customer
{
    const ENTITY_TYPE_CODE = 'customers_freelectures';

    protected $_entityTypeCode = 'customers_freelectures';

    /*
     * Variable below contains names of the columns as well as the attribute code that will be placed inside of each column
     * The key name is the column name and the value name is the attribute code.  You can add or remove items from this array and those values will
     * be automatically added or removed from this export profile.
     */
    protected $_freelectureAttributes = array(
        'web_prospect_id'           => 'web_prospect_id',
        'dax_customer_id'           => 'dax_customer_id',
        'web_customer_id'           => 'entity_id',
        'date_collected'            => 'free_lectures_date_collected',
        'initial_source'            => 'free_lectures_initial_source',
        'email_address'             => 'email',
        'initialuseragent'          => 'free_lect_initial_user_agent',
        'last_date_collected'       => 'free_lect_last_date_collected',
        'subscribe_status'          => 'subscribe_status',
        'email_verified'            => 'email_verified',
        'date_verified'             => 'date_verified',
        'confirmation_guid'         => 'confirmation_guid',
        'is_account_at_signup'      => 'is_account_at_signup',
        'date_unsubscribed'         => 'free_lect_date_unsubscribed',
        'subscribe_status'          => 'free_lect_subscribe_status',
    );

    /**
     * EAV entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return $this->_entityTypeCode;
    }

    public function __construct()
    {
        $this->setEntityTypeCode('customer'); //temporarily setting entity type code to catalog product, parent constructor needs it to retrieve entityTypeId!
        parent::__construct();
        $this->setEntityTypeCode(self::ENTITY_TYPE_CODE); //entity type id not referenced in any significant way in this class or in any parent, therefore, okay to change!
    }

    public function setEntityTypeCode($entityTypeCode)
    {
        $this->_entityTypeCode = $entityTypeCode;
    }

    /**
     * Export process.
     *
     * @return string
     */
    public function export()
    {
        $collection     = $this->_prepareEntityCollection(Mage::getResourceModel('customer/customer_collection'));
        $validAttrCodes = array_values($this->_freelectureAttributes);
        $writer         = $this->getWriter();

        $writer->setHeaderCols(array_keys($this->_freelectureAttributes));

        foreach ($collection as $itemId => $item) { // go through all customers
            $row = array();

            // go through all valid attribute codes
            foreach ($validAttrCodes as $attrCode) {
                $attrValue = $item->getData($attrCode);

                $this->customFieldMapping($attrCode, $attrValue);

                if (isset($this->_attributeValues[$attrCode])
                    && isset($this->_attributeValues[$attrCode][$attrValue])
                ) {
                    $attrValue = $this->_attributeValues[$attrCode][$attrValue];
                }
                if (null !== $attrValue) {
                    $row[array_search($attrCode, $this->_freelectureAttributes)] = $attrValue;
                }
            }
            $row[self::COL_WEBSITE] = $this->_websiteIdToCode[$item['website_id']];
            $row[self::COL_STORE]   = $this->_storeIdToCode[$item['store_id']];

            if(isset($row['web_prospect_id']) && $row['web_prospect_id']) { //row should NOT be exported unless this value exists.
                $writer->writeRow($row);
            }

        }
        return $writer->getContents();
    }

    public function customFieldMapping($attrCode, &$attrValue)
    {
        if($attrCode == 'email_verified') {
            if($attrValue == null) {
                $attrValue = 0;
            }
        }
    }

    /**
     * Apply filter to collection and add not skipped attributes to select.
     *
     * @param Mage_Eav_Model_Entity_Collection_Abstract $collection
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _prepareEntityCollection(Mage_Eav_Model_Entity_Collection_Abstract $collection)
    {
        // this function is rewritten slightly so that default billing and shipping addresses attributes
        $collection = Mage_ImportExport_Model_Export_Entity_Abstract::_prepareEntityCollection($collection);
        $collection->addAttributeToFilter('web_prospect_id',array('notnull' => true));
        return $collection;
    }

    protected function _getExportAttrCodes()
    {
        $attributeCodes = parent::_getExportAttrCodes();

        $freelectureAttributes = array_values($this->_freelectureAttributes);

        foreach($attributeCodes as $attributeCode) {
            if(!in_array($attributeCode, $freelectureAttributes)) {
                unset($attributeCodes[$attributeCode]);
            }
        }

        return $attributeCodes;
    }

    /**
     * Entity attributes collection getter.
     *
     * @return Mage_Customer_Model_Entity_Attribute_Collection
     */
    public function getAttributeCollection()
    {
        $collection = Mage::getResourceModel('customer/attribute_collection');
        $collection->getSelect()->where('attribute_code IN(?)', array_values($this->_freelectureAttributes));
        return $collection;
    }

}