<?php
/**
 * DataMart integration
 *
 * @method int getId() getId() Returns customer upsell entity id
 * @method string getSegmentGroup() getSegmentGroup() Returns segment group
 * @method int getCourseId() getCourseId() Returns course id
 * @method float getSortOrder() getSortOrder() Returns sort order
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_CustomerUpsell extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'customer_upsell';

    protected $_eventPrefix = 'customer_upsell';
    protected $_eventObject = 'customerUpsell';

    protected function _construct()
    {
        $this->_init('tgc_datamart/customerUpsell');
    }
}
