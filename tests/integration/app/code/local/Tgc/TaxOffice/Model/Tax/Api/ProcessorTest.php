<?php
require_once dirname(__FILE__) .'/../../../../../../../../bootstrap.php';
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_ProcessorTest extends Magento_PHPUnit_TestCase
{
    protected $_config;

    /**
     * @param string $wsdl
     * @param string $user
     * @param string $password
     * @return Tgc_TaxOffice_Model_Tax_Api_Processor
     */
    protected function _createProcessor($wsdl, $entity, $division, $shipFrom, $testTransaction, $customerType, $providerType)
    {
        $storeId = 1;

        $this->_config = Mage::getModel('tgc_taxOffice/config', array('store_id' => $storeId));

        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_API_WSDL,
            $wsdl,
            $storeId
        );

        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_IS_ENABLED, 1, $storeId);
        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_API_ENTITY_ID, $entity, $storeId);
        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_API_DIVISION_ID, $division, $storeId);
        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_API_SHIP_FROM_ZIP_CODE, $shipFrom, $storeId);
        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_API_TEST, $testTransaction, $storeId);
        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_API_DEBUG, 0, $storeId);
        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_API_CUSTOMER_TYPE, $customerType, $storeId);
        $this->setStoreConfig(Tgc_TaxOffice_Model_Config::XML_API_PROVIDE_TYPE, $providerType, $storeId);

        return new Tgc_TaxOffice_Model_Tax_Api_Processor(array(
            'config' => $this->_config
        ));
    }

    /**
     * @return Tgc_TaxOffice_Model_Tax_Api_Processor
     */
    protected function _createMockProcessor()
    {
        return $this->_createProcessor(
            rtrim(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, false), '/')
                . '/tests/service/tgc/taxoffice/server.php?wsdl=1',
            'foo',
            'bar',
            '20151',
            1,
            "00001",
            "10000"
        );
    }

    public function testCalculateSuccessServiceMock()
    {
        $processor = $this->_createMockProcessor();
        $address = Mage::getModel('sales/quote_address');
        $address->setCity('New York')
            ->setCountryId('US')
            ->setRegion('NY')
            ->setPostcode('10010')
            ->setStreet('59 Lexington street');

        /* @var $items Tgc_TaxOffice_Model_Tax_Api_LineItemCollection */
        $items = Mage::getModel('tgc_taxOffice/tax_api_lineItemCollection', array('config' => $this->_config));

        $product1 = $this->getModelMockBuilder('catalog/product')
            ->setMethods(array('getId'))
            ->disableOriginalConstructor()
            ->getMock();
        $product1->setMediaFormat(1);
        $product1->setTypeId('simple');
        $product1->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(100));


        $product2 = $this->getModelMockBuilder('catalog/product')
            ->setMethods(array('getId'))
            ->disableOriginalConstructor()
            ->getMock();
        $product2->setTypeId(Enterprise_GiftCard_Model_Catalog_Product_Type_Giftcard::TYPE_GIFTCARD);
        $product2->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(101));

        $quoteItem1 = Mage::getModel('sales/quote_item')
            ->setStoreId(1)
            ->setId(22)
            ->setName('Product1')
            ->setProductType('simple')
            ->setProduct($product1)
            ->setRowTotal(100)
            ->setPrice(100)
            ->setQty(1);

        $quoteItem2 = Mage::getModel('sales/quote_item')
            ->setStoreId(1)
            ->setId(23)
            ->setName('Product2')
            ->setProductType(Enterprise_GiftCard_Model_Catalog_Product_Type_Giftcard::TYPE_GIFTCARD)
            ->setProduct($product2)
            ->setRowTotal(50)
            ->setPrice(25)
            ->setQty(2);

        $skuConverter = $this->getModelMockBuilder('tgc_taxOffice/tax_api_lineItem_skuConverter')
            ->setMethods(array('_getProductMediaFormatOptionText'))
            ->getMock();
        $skuConverter->expects($this->any())
            ->method('_getProductMediaFormatOptionText')
            ->will($this->returnValue('DVD'));

        $lineItem1 = Mage::getModel('tgc_taxOffice/tax_api_lineItem',
            array(
                'config' => $this->_config,
                'item' => $quoteItem1,
                'sku_converter' => $skuConverter
            )
        );
        $lineItem2 = Mage::getModel('tgc_taxOffice/tax_api_lineItem',
            array(
                'config' => $this->_config,
                'item' => $quoteItem2,
                'sku_converter' => $skuConverter
            )
        );
        $items->add($lineItem1);
        $items->add($lineItem2);

        $result = $processor->calculate($address, $items, 10, 0);

        $this->assertEquals(round((100+50+10)*0.07,2), $result->getTotalTaxAmount());
    }
}