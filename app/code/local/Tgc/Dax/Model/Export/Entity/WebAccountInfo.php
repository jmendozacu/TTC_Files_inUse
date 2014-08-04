<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Export_Entity_WebAccountInfo extends Mage_ImportExport_Model_Export_Entity_Customer
{
    const ENTITY_TYPE_CODE = 'customers_webaccountinfo';

    protected $_entityTypeCode = 'customers_webaccountinfo';

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
        $this->setEntityTypeCode('customer');
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
     * We are overriding this entire function for one reason, which is to get the customer entity information to appear
     * on every address line, instead of the default, which is blank fields.
     *
     * @return string
     */
    public function export()
    {
        $collection     = $this->_prepareEntityCollection(Mage::getResourceModel('customer/customer_collection'));
        $validAttrCodes = $this->_getExportAttrCodes();
        $writer         = $this->getWriter();
        $defaultAddrMap = Mage_ImportExport_Model_Import_Entity_Customer_Address::getDefaultAddressAttrMapping();

        // prepare address data
        $addrAttributes = array();
        $addrColNames   = array();
        $customerAddrs  = array();

        foreach (Mage::getResourceModel('customer/address_attribute_collection')
                     ->addSystemHiddenFilter()
                     ->addExcludeHiddenFrontendFilter() as $attribute) {
            $options  = array();
            $attrCode = $attribute->getAttributeCode();

            if ($attribute->usesSource() && 'country_id' != $attrCode) {
                foreach ($attribute->getSource()->getAllOptions(false) as $option) {
                    foreach (is_array($option['value']) ? $option['value'] : array($option) as $innerOption) {
                        if (strlen($innerOption['value'])) { // skip ' -- Please Select -- ' option
                            $options[$innerOption['value']] = $innerOption['label'];
                        }
                    }
                }
            }
            $addrAttributes[$attrCode] = $options;
            $addrColNames[] = Mage_ImportExport_Model_Import_Entity_Customer_Address::getColNameForAttrCode($attrCode);
        }
        $address_collection = $this->_addLastRunDateFilter(Mage::getResourceModel('customer/address_collection')->addAttributeToSelect('*'));
        foreach ($address_collection as $address) {
            $addrRow = array();

            foreach ($addrAttributes as $attrCode => $attrValues) {
                if (null !== $address->getData($attrCode)) {
                    $value = $address->getData($attrCode);

                    if ($attrValues) {
                        $value = $attrValues[$value];
                    }
                    $column = Mage_ImportExport_Model_Import_Entity_Customer_Address::getColNameForAttrCode($attrCode);
                    $addrRow[$column] = $value;
                }
            }
            $customerAddrs[$address['parent_id']][$address->getId()] = $addrRow;
        }

        // create export file
        $writer->setHeaderCols(array_merge(
            $this->_permanentAttributes, $validAttrCodes,
            array('password'), $addrColNames,
            array_keys($defaultAddrMap)
        ));
        foreach ($collection as $itemId => $item) { // go through all customers
            $row = array();

            // go through all valid attribute codes
            foreach ($validAttrCodes as $attrCode) {
                $attrValue = $item->getData($attrCode);

                if (isset($this->_attributeValues[$attrCode])
                    && isset($this->_attributeValues[$attrCode][$attrValue])
                ) {
                    $attrValue = $this->_attributeValues[$attrCode][$attrValue];
                }
                if (null !== $attrValue) {
                    $row[$attrCode] = $attrValue;
                }
            }
            $row[self::COL_WEBSITE] = $this->_websiteIdToCode[$item['website_id']];
            $row[self::COL_STORE]   = $this->_storeIdToCode[$item['store_id']];

            // addresses injection
            $defaultAddrs = array();

            foreach ($defaultAddrMap as $colName => $addrAttrCode) {
                if (!empty($item[$addrAttrCode])) {
                    $defaultAddrs[$item[$addrAttrCode]][] = $colName;
                }
            }
            if (isset($customerAddrs[$itemId])) {
                while (($addrRow = each($customerAddrs[$itemId]))) {
                    if (isset($defaultAddrs[$addrRow['key']])) {
                        foreach ($defaultAddrs[$addrRow['key']] as $colName) {
                            $row[$colName] = 1;
                        }
                    }
                    $writer->writeRow(array_merge($row, $addrRow['value']));

                    // Deleting the $row array is what removes the entity data from multi-address customer accounts.  TGC
                    // wants this data persisted on every row.  However, we need to reset the default shipping and default billing
                    // addresses because we cannot have this data persist from row to row on multiple addresses.  This loop
                    // nulls out the _default_billing_address_ and _default_shipping_address_ items in $row
                    foreach($defaultAddrMap as $key => $val)
                    {
                        if(isset($row[$key])) $row[$key] = '';
                    }
                }
            } else {
                $writer->writeRow($row);
            }
        }
        return $writer->getContents();
    }

    /**
     * Apply filter to collection and add not skipped attributes to select.
     *
     * @param Mage_Eav_Model_Entity_Collection_Abstract $collection
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _prepareEntityCollection(Mage_Eav_Model_Entity_Collection_Abstract $collection)
    {
        $collection = $this->_addLastRunDateFilter($collection);
        $collection = parent::_prepareEntityCollection($collection);
        return $collection;
    }

    /**
     * Limit collection by last successful run date of export profile
     */
    protected function _addLastRunDateFilter($collection)
    {
        // Get datetime of last successful export
        $op_collection = Mage::getResourceModel('enterprise_importexport/scheduled_operation_collection');
        $op_collection->getSelect()->where('entity_type = ?', self::ENTITY_TYPE_CODE);
        $operation = $op_collection->getFirstItem();
        $last_run = $operation->getLastRunDate();

        // Select customer profiles that have been updated since the last scheduled run.
        $collection->getSelect()->where('updated_at >= ?', $last_run);
        return $collection;
    }
}