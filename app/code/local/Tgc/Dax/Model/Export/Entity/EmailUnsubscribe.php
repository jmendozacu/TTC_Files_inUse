<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Export_Entity_EmailUnsubscribe extends Mage_ImportExport_Model_Export_Entity_Abstract
{
    //the attribute names in the db
    const WEB_KEY              = 'web_key';
    const CUSTOMER_ID          = 'customer_id';
    const EMAIL                = 'email';
    const DATE                 = 'unsubscribe_date';
    const EMAIL_CAMPAIGN       = 'email_campaign';

    //the names used in the export file
    const COL_WEB_KEY          = 'web_key';
    const COL_CUSTOMER_ID      = 'dax_customer_id';
    const COL_EMAIL            = 'email';
    const COL_DATE             = 'date';
    const COL_EMAIL_CAMPAIGN   = 'email_campaign';
    const COL_ACTION           = 'action';
    const COL_OPTIN_ID         = 'optin_id';

    const ACTION_SUBSCRIBE     = 'Subscribe';
    const ACTION_UNSUBSCRIBE   = 'Unsubscribe';
    const SUBSCRIBE_CAMPAIGN   = 'newsletter_subscribe';
    const DEFAULT_WEBKEY       = 'Magento-webkey';
    const OPTIN_ID             = 'OfferEmail';

    /**
     * Permanent entity columns.
     *
     * @var array
     */
    protected $_permanentAttributes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('write');

        $this->_permanentAttributes = array(
            self::COL_WEB_KEY,
            self::COL_CUSTOMER_ID,
            self::COL_EMAIL,
            self::COL_DATE,
            self::COL_EMAIL_CAMPAIGN,
            self::COL_ACTION,
            self::COL_OPTIN_ID,
        );
    }

    public function export()
    {
        $resource   = Mage::getResourceModel('tgc_dax/emailUnsubscribe');
        $resource->archiveRecords();
        $collection = $resource->getCollection()
            ->addFieldToSelect('*')
            ->addExportFilter();

        $newsletters = Mage::getModel('newsletter/subscriber')
            ->getCollection()
            ->addFieldToFilter('needs_export', array('eq' => 1))
            ->addFieldToSelect('subscriber_id')
            ->addFieldToSelect('subscriber_email')
            ->addFieldToSelect('subscriber_status')
            ->addFieldToSelect('change_status_at');

        $newsletters->getSelect()->joinLeft(
                array('customer' => 'customer_entity'),
                'customer.entity_id = main_table.customer_id',
                array('dax_customer_id', 'web_user_id')
            )->columns(array('action' => new Zend_Db_Expr(
                'IF(main_table.subscriber_status = ' . Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED . ', "'
                . self::ACTION_UNSUBSCRIBE . '", "' . self::ACTION_SUBSCRIBE . '")'))
            );

        $writer = $this->getWriter();
        $writer->setHeaderCols($this->_permanentAttributes);

        foreach ($collection as $item) {
            $row = array();
            $row[self::COL_WEB_KEY]        = $item->getData(self::WEB_KEY);
            $row[self::COL_CUSTOMER_ID]    = $item->getData(self::CUSTOMER_ID);
            $row[self::COL_EMAIL]          = $item->getData(self::EMAIL);
            $row[self::COL_DATE]           = $item->getData(self::DATE);
            $row[self::COL_EMAIL_CAMPAIGN] = $item->getData(self::EMAIL_CAMPAIGN);
            $row[self::COL_ACTION]         = self::ACTION_UNSUBSCRIBE;
            $row[self::COL_OPTIN_ID]       = self::OPTIN_ID;
            $writer->writeRow($row);
        }

        $idsToUpdate = array();
        foreach ($newsletters as $item) {
            $idsToUpdate[] = $item->getSubscriberId();
            $row = array();
            $row[self::COL_WEB_KEY]        = $item->getWebUserId();
            $row[self::COL_CUSTOMER_ID]    = $item->getDaxCustomerId();
            $row[self::COL_EMAIL]          = $item->getSubscriberEmail();
            $row[self::COL_DATE]           = $item->getChangeStatusAt();
            $row[self::COL_EMAIL_CAMPAIGN] = self::SUBSCRIBE_CAMPAIGN;
            $row[self::COL_ACTION]         = $item->getAction();
            $row[self::COL_OPTIN_ID]       = self::OPTIN_ID;
            $writer->writeRow($row);
        }

        $this->_updateNewsletterSubscriberStatus($idsToUpdate);

        return $writer->getContents();
    }

    public function getAttributeCollection()
    {
        return Mage::getResourceModel('eav/entity_attribute_collection')
            ->addFieldToFilter('entity_type_id', array('eq' => 0));
    }

    public function getEntityTypeCode()
    {
        return 'subscription_updates';
    }

    private function _getResource()
    {
        return Mage::getSingleton('core/resource');
    }

    private function _getWriteAdapter()
    {
        return $this->_getResource()->getConnection('write');
    }

    private function _updateNewsletterSubscriberStatus($idsToUpdate)
    {
        $adapter = $this->_getWriteAdapter();
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
