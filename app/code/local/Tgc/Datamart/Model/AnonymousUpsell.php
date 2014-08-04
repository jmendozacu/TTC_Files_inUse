<?php
/**
 * DataMart integration
 *
 * @method int getId() getId() Returns anonymous upsell entity id
 * @method int getSubjectId() getSubjectId() Returns subject id
 * @method int getCourseId() getCourseId() Returns course id
 * @method float getSortOrder() getSortOrder() Returns sort order
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Model_AnonymousUpsell extends Mage_Core_Model_Abstract
{
    const CACHE_TAG         = 'anonymous_upsell';

    protected $_eventPrefix = 'anonymous_upsell';
    protected $_eventObject = 'anonymousUpsell';

    protected function _construct()
    {
        $this->_init('tgc_datamart/anonymousUpsell');
    }
}
