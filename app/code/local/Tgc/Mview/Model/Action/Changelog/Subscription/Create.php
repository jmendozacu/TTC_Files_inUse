<?php

/**
 * Changelog subscribe action class
 *
 * @category    Enterprise
 * @package     Enterprise_Mview
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Tgc_Mview_Model_Action_Changelog_Subscription_Create
    extends Enterprise_Mview_Model_Action_Changelog_Subscription_Create
{
    protected $_customTriggers = array();

    /**
     * Constructor
     *
     * @param array $args
     * @throws InvalidArgumentException
     */
    public function __construct(array $args)
    {
        parent::__construct($args);
        if (isset($args['custom_triggers'])) {
            $this->_customTriggers = $args['custom_triggers'];
        }
    }

    /**
     * Generate and return trigger body's row
     *
     * @param string $event
     * @param Varien_Object $subscriber
     * @return string
     */
    protected function _getInsertRow($event, Varien_Object $subscriber)
    {
        $customTrigger = $subscriber->getResource()->getCustomTrigger($event, $subscriber->getSubscriberId());
        if ($customTrigger) {
            return $customTrigger;
        }

        return parent::_getInsertRow($event, $subscriber);
    }

    /**
     * Initialize and return subscriber model
     *
     * @return Enterprise_Mview_Model_Subscriber
     */
    protected function _getSubscriber()
    {
        $subscriber = parent::_getSubscriber();
        $subscriber->setCustomTriggers($this->_customTriggers);
        return $subscriber;
    }
}
