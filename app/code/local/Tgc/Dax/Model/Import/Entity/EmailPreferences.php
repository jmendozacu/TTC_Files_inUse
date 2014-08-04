<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_EmailPreferences extends Mage_ImportExport_Model_Import_Entity_Abstract
{
    //the attribute names in the db
    const DAX_CUSTOMER_ID      = 'dax_customer_id';
    const OPTIN_ID             = 'optin_id';
    const SUBSCRIPTION_STATUS  = 'subscription_status';

    //the names used in the import file
    const COL_DAX_CUSTOMER_ID      = 'dax_customer_id';
    const COL_OPTIN_ID             = 'optin_id';
    const COL_SUBSCRIPTION_STATUS  = 'subscription_status';

    const ACTION_SUBSCRIBE         = 'Subscribe';
    const ACTION_UNSUBSCRIBE       = 'Unsubscribe';
    const SUBSCRIBE_CAMPAIGN       = 'newsletter_subscribe';
    const VALID_OPTIN_ID                 = 'OfferEmail';

    const ERROR_INVALID_DAX_CUSTOMER_ID      = 4;

    /**
     * Permanent entity columns.
     *
     * @var array
     */
    protected $_permanentAttributes;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        /** @var _dataSourceModel Mage_ImportExport_Model_Resource_Import_Data */
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection      = Mage::getSingleton('core/resource')->getConnection('write');

        $this->_permanentAttributes = array(
            self::COL_DAX_CUSTOMER_ID,
            self::COL_OPTIN_ID,
            self::COL_SUBSCRIPTION_STATUS,
        );

        $this->_initErrorMessages();
    }

    private function _initErrorMessages()
    {
        $this->addMessageTemplate(self::ERROR_INVALID_DAX_CUSTOMER_ID, 'Empty Dax customer ID');
    }

    /**
     * We use ucfirst column names so they are all particular
     *
     * @param string $attrCode
     * @return bool
     */
    public function isAttributeParticular($attrCode)
    {
        return in_array($attrCode, $this->_permanentAttributes);
    }

    public function getEntityTypeCode()
    {
        return 'email_preferences';
    }

    public function validateRow(array $rowData, $rowNum)
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            return true;
        }

        try {
            if (!Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {
                $this->_map($rowData);
            }
            return true;
        } catch (InvalidArgumentException $e) {
            $this->addRowError($e->getMessage(), $rowNum);
            return false;
        }
    }

    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            return $this->_removeSubscriptions();
        } else if (Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->_updateSubscriptions();
        } else {
            $this->_saveSubscriptions();
        }

        return true;
    }

    protected function _updateSubscriptions() {
        $this->_saveSubscriptions();
    }

    /**
     * Save new subscription
     */
    private function _saveSubscriptions()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $data = array();
            $ids = array();
            foreach ($bunch as $rowNum => $rowData) {
                if ($rowNum) {
                    $data[$rowData[self::COL_DAX_CUSTOMER_ID]] = array(
                        self::OPTIN_ID              => $rowData[self::COL_OPTIN_ID],
                        self::SUBSCRIPTION_STATUS   => $rowData[self::COL_SUBSCRIPTION_STATUS],
                    );
                    $ids[] = $rowData[self::COL_DAX_CUSTOMER_ID];
                }
            }

            try {
                $customers = Mage::getModel('customer/customer')->getCollection()
                    ->addAttributeToSelect('dax_customer_id')
                    ->addFieldToFilter('dax_customer_id',array('in' => $ids));
                /** @var $customer Mage_Customer_Model_Customer */
                foreach ($customers as $customer) {
                    /** @var $subscription Mage_Newsletter_Model_Subscriber */
                    $subscription = Mage::getModel('newsletter/subscriber');
                    // Data to avoid send mails during import
                    $subscription->setImportMode(true);

                    // Valid that Optin ID is equal to Valid Optin Id
                    if (strtolower($data[$customer->getDaxCustomerId()][self::OPTIN_ID]) == strtolower(self::VALID_OPTIN_ID)) {
                        // If Subs Status is Subscribe
                        if (strtolower($data[$customer->getDaxCustomerId()][self::SUBSCRIPTION_STATUS]) == strtolower(self::ACTION_SUBSCRIBE)) {
                            $customer->setIsSubscribed(Mage_Customer_Model_Customer::SUBSCRIBED_YES);
                            $customer->save();
                            $subscription->subscribeCustomer($customer);
                            // If Subs Status is Unsubscribe
                        } elseif (strtolower($data[$customer->getDaxCustomerId()][self::SUBSCRIPTION_STATUS]) == strtolower(self::ACTION_UNSUBSCRIBE)) {
                            $subscription->loadByCustomer($customer);
                            // Simulation of subscription to create subscription before unSubscribe customer
                            if (!$subscription->getId()) {
                                $customer->setIsSubscribed(Mage_Customer_Model_Customer::SUBSCRIBED_YES);
                                $subscription->subscribeCustomer($customer);
                            }
                            $subscription->unsubscribe();
                        }
                        $subscription->setNeedsExport(false);
                        $subscription->save();
                    }
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    /**
     * Delete subscription
     */
    private function _removeSubscriptions()
    {
        try {
            while ($bunch = $this->_dataSourceModel->getNextBunch()) {
                $data = array();
                $ids = array();
                foreach ($bunch as $rowNum => $rowData) {
                    if ($rowNum) {
                        $data[$rowData[self::COL_DAX_CUSTOMER_ID]] = array(
                            self::OPTIN_ID              => $rowData[self::COL_OPTIN_ID],
                            self::SUBSCRIPTION_STATUS   => $rowData[self::COL_SUBSCRIPTION_STATUS],
                        );
                        $ids[] = $rowData[self::COL_DAX_CUSTOMER_ID];
                    }
                }

                try {
                    $customers = Mage::getModel('customer/customer')->getCollection()
                        ->addAttributeToSelect('dax_customer_id')
                        ->addFieldToFilter('dax_customer_id',array('in' => $ids));
                    /** @var $customer Mage_Customer_Model_Customer */
                    foreach ($customers as $customer) {
                        /** @var $subscription Mage_Newsletter_Model_Subscriber */
                        $subscription = Mage::getModel('newsletter/subscriber');
                        // Data to avoid send mails during import
                        $subscription->loadByCustomer($customer);
                        if ($subscription->getId()) {
                            $subscription->delete();
                        }
                    }
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    protected function _map(array $row)
    {
        $daxCustomerId = (int)$row[self::COL_DAX_CUSTOMER_ID];
        if (!$daxCustomerId) {
            throw new InvalidArgumentException('Invalid dax customer ID', self::ERROR_INVALID_DAX_CUSTOMER_ID);
        }

        return array(
            self::DAX_CUSTOMER_ID       => $row[self::COL_DAX_CUSTOMER_ID],
            self::OPTIN_ID              => $row[self::COL_OPTIN_ID],
            self::SUBSCRIPTION_STATUS   => $row[self::COL_SUBSCRIPTION_STATUS],
        );
    }

    private function _updateNewsletterSubscriberStatus($idsToUpdate)
    {
        $adapter = $this->_connection;
        $update  = array(
            'needs_export' => 0,
        );

        try {
            $where = $adapter->quoteInto('subscriber_id IN (?)', array_filter($idsToUpdate));
            $adapter->update('newsletter_subscriber', $update, $where);
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
}
