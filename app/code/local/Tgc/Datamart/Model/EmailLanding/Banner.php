<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Model_EmailLanding_Banner extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'tgc_datamart_landing_banner';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'banner';

    protected function _construct()
    {
        $this->_init('tgc_datamart/emailLanding_banner');
    }

    /**
     * Load banner by ad code
     *
     * @param integer $adCode
     * @return \Tgc_Datamart_Model_EmailLanding_Banner
     */
    public function loadByAdCode($adCode)
    {
        $this->_getResource()->loadByAdCode($this, $adCode);
        $this->_afterLoad();
        $this->setOrigData();
        $this->_hasDataChanges = false;
        return $this;
    }
}
