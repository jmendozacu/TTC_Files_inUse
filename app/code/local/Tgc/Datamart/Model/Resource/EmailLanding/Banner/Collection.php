<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Model_Resource_EmailLanding_Banner_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('tgc_datamart/emailLanding_banner');
        $this->_map['fields']['ad_code'] = 'adcode_table.ad_code';
    }

    /**
     * Add filter by ad code
     *
     * @param int $adCode
     * @return Tgc_Datamart_Model_Resource_EmailLanding_Banner_Collection
     */
    public function addAdCodeFilter($adCode)
    {
        if (!is_array($adCode)) {
            $adCode = array($adCode);
        }

        $this->addFilter('ad_code', array('in' => $adCode), 'public');

        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        $countSelect->reset(Zend_Db_Select::GROUP);

        return $countSelect;
    }

    /**
     * Join ad_code relation table if there is ad_code filter
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('ad_code')) {
            $this->getSelect()->join(
                array('adcode_table' => $this->getTable('tgc_datamart/landing_banner_adcode')),
                'main_table.banner_id = adcode_table.banner_id',
                array()
            )->group('main_table.banner_id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }
}
