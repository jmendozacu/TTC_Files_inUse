<?php
/**
 * Catalog Codes import entity
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_BestSellers extends Mage_ImportExport_Model_Import_Entity_Abstract
{
    const COL_COURSE_ID                         = 'course_id';
    const COL_GUEST_BESTSELLER_RANK             = 'guest_bestsellers_rank';
    const COL_AUTHENTICATED_BESTSELLER_RANK     = 'authenticated_bestsellers_rank';
    const COL_WEBSITE                           = 'website';
    
    public function __construct()
    {
        $this->_dataSourceModel  = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
    }
    
    /**
     * Validate a row data depending on the action
     *
     * @param array $rowData
     * @param int   $rowNum
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validateRow(array $rowData, $rowNum)
    {
        try {
            
            if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
                return true;
            }
            
            if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {
                
                if (
                    empty($rowData[self::COL_COURSE_ID]) || 
                    !Zend_Validate::is($rowData[self::COL_COURSE_ID], 'Digits')
                ) {
                    $message = Mage::helper('tgc_dax')->__('Course ID is not valid');
                    throw new InvalidArgumentException($message);
                }
                
                if (
                    empty($rowData[self::COL_GUEST_BESTSELLER_RANK]) ||
                    !Zend_Validate::is($rowData[self::COL_GUEST_BESTSELLER_RANK], 'Digits')
                ) {
                    $message = Mage::helper('tgc_dax')->__('Guest Best Seller Rank is not valid.');
                    throw new InvalidArgumentException($message);
                }
                
                if (
                    empty($rowData[self::COL_AUTHENTICATED_BESTSELLER_RANK]) ||
                    !Zend_Validate::is($rowData[self::COL_AUTHENTICATED_BESTSELLER_RANK], 'Digits')
                ) {
                    $message = Mage::helper('tgc_dax')->__('Authenticated Best Seller Rank is not valid.');
                    throw new InvalidArgumentException($message);
                }
                
                if (
                   empty($rowData[self::COL_WEBSITE]) ||
                   $this->_getWebsiteBasedOnValue($rowData[self::COL_WEBSITE]) == null
              ) {
                    $message = Mage::helper('tgc_dax')->__('Website is not valid.');
                    throw new InvalidArgumentException($message);
                }
            }
            
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }
    
    /**
     * Import Data
     * 
     */
    protected function _importData()
    {
        if (
            Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior() ||
            Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()
        ) {
            
            $this->_saveData();
        }
    }
    
    /**
     * Save Data
     */
    protected function _saveData()
    {
        $helper = Mage::helper('tgc_dax');
        
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $data = array();
            $row = 0;
            foreach ($bunch as $rowData) { $row++;
                
                $data = $rowData;
                
                $collection = Mage::getModel('catalog/product')
                    ->setStore($this->_getWebsiteBasedOnValue($rowData[self::COL_WEBSITE]))
                    ->getCollection()
                    ->addAttributeToFilter('type_id', 'configurable')
                    ->addAttributeToFilter('course_id', $rowData[self::COL_COURSE_ID]);
                
                if (count($collection) > 0) {
                    foreach ($collection as $product) {
                        $productForStore = Mage::getModel('catalog/product');
                        $productForStore->setStoreId($this->_getWebsiteBasedOnValue($rowData[self::COL_WEBSITE]));
                        $productForStore->load($product->getId());
                        $productForStore->setData(
                            'guest_bestsellers',
                            $rowData[self::COL_GUEST_BESTSELLER_RANK]
                        );
                        $productForStore->setData(
                            'authenticated_bestsellers',
                            $rowData[self::COL_AUTHENTICATED_BESTSELLER_RANK]
                        );
                        $productForStore->getResource()->saveAttribute(
                            $productForStore,
                            'guest_bestsellers'
                        );
                        $productForStore->getResource()->saveAttribute(
                            $productForStore,
                            'authenticated_bestsellers'
                        );
                    }
                } else {
                    $this->addRowError($helper->__('Course does not exists'), $row);
                }
            }
        }
    }
    
    /**
     * Get Website ID based on website column value
     * 
     * @param string $website
     * @return int
     */
    protected function _getWebsiteBasedOnValue($website)
    {
        $websiteId = null;
        $ids = array(
            'US'    => 1,
            'UK'    => 2,
            'AU'    => 3
        );
        
        if (isset($ids[$website]) && !empty($ids[$website])) {
            $websiteId = $ids[$website];
        }
        
        return $websiteId;
    }
    
    /**
     * Get Entity Type Code
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'best_sellers';
    }
}
