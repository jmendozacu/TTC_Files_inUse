<?php
require_once dirname(__FILE__) .'/../../../../../../../../bootstrap.php';
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_StrongMail
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_StrongMail_Model_Api_Mailer_ProcessorTest extends Magento_PHPUnit_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $storeId = 1;

        $config = Mage::getModel('tgc_strongMail/api_mailer_config', array('store_id' => $storeId));

        $this->setStoreConfig(Tgc_StrongMail_Model_Api_Mailer_Config::XML_API_WSDL,
            rtrim(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, false), '/')
                . '/tests/service/tgc/strongmail/server.php?wsdl=1',
            $storeId
        );
        $this->setStoreConfig(Tgc_StrongMail_Model_Api_Mailer_Config::XML_API_USER, 'foo', $storeId);
        $this->setStoreConfig(Tgc_StrongMail_Model_Api_Mailer_Config::XML_API_PASSWORD, 'bar', $storeId);
        $this->setStoreConfig(Tgc_StrongMail_Model_Api_Mailer_Config::XML_API_COMPANY, 'tgc', $storeId);

        $this->_processor = new Tgc_StrongMail_Model_Api_Mailer_Processor(array(
            'config' => $config
        ));
    }

    /**
     * @param string $wsdl
     * @param string $user
     * @param string $password
     * @param string $company
     * @return Tgc_StrongMail_Model_Api_Mailer_Processor
     */
    protected function _createProcessor($wsdl, $user, $password, $company)
    {
        $storeId = 1;

        $config = Mage::getModel('tgc_strongMail/api_mailer_config', array('store_id' => $storeId));

        $this->setStoreConfig(Tgc_StrongMail_Model_Api_Mailer_Config::XML_API_WSDL,
            $wsdl,
            $storeId
        );
        $this->setStoreConfig(Tgc_StrongMail_Model_Api_Mailer_Config::XML_API_USER, $user, $storeId);
        $this->setStoreConfig(Tgc_StrongMail_Model_Api_Mailer_Config::XML_API_PASSWORD, $password, $storeId);
        $this->setStoreConfig(Tgc_StrongMail_Model_Api_Mailer_Config::XML_API_COMPANY, $company, $storeId);

        return new Tgc_StrongMail_Model_Api_Mailer_Processor(array(
            'config' => $config
        ));
    }

    /**
     * @return Tgc_StrongMail_Model_Api_Mailer_Processor
     */
    protected function _createMockProcessor()
    {
        return $this->_createProcessor(
            rtrim(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, false), '/')
                . '/tests/service/tgc/strongmail/server.php?wsdl=1',
            'foo',
            'bar',
            'tgc'
        );
    }

    /**
     * @return Tgc_StrongMail_Model_Api_Mailer_Processor
     */
    protected function _createRealServiceProcessor()
    {
        return $this->_createProcessor(
            'https://67.129.116.186/sm/services/mailing/2009/03/02?wsdl',
            'MagentoMailUser',
            'teachco',
            'TTC'
        );
    }

    /**
     *
     */
    public function testGetMailIdSuccessWithMock()
    {
        $processor = $this->_createMockProcessor();
        $id = $processor->getMailingId('new_order_registered');
        $this->assertEquals(123, $id);
    }

    public function testTxnSendSuccessWithMock()
    {
        $processor = $this->_createMockProcessor();
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo('john@example.com', 'John Doe');
        $success = $processor->txnSend($emailInfo, 123, array('ORDER_TOTAL' => '$125.00'));
        $this->assertTrue($success);
    }

    public function testGetMailIdSuccessRealService()
    {
        $processor = $this->_createRealServiceProcessor();
        $id = $processor->getMailingId('ProspectValidationForMagentoTesting');
        $this->assertEquals(374000, $id);
    }

    public function testTxnSendSuccessRealService()
    {
        $processor = $this->_createRealServiceProcessor();
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo('john@example.com', 'John Doe');
        $mailingId = $processor->getMailingId('ProspectValidationForMagentoTesting');
        $this->assertEquals(374000, $mailingId);
        $success = $processor->txnSend($emailInfo,  $mailingId, array('ORDER_TOTAL' => '$125.00'));
        $this->assertTrue($success);
    }
}