<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Datamart_Model_Resource_EmailLanding_Mediacode_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('tgc_datamart/emailLanding_mediacode');
    }

    /**
     * Join aliases table
     *
     * @return \Tgc_Datamart_Model_Resource_EmailLanding_Mediacode_Collection
     */
    public function joinAliases()
    {
        $fromPart = $this->getSelect()->getPart(Zend_Db_Select::FROM);
        if (!isset($fromPart['alias_table'])) {
            $this->getSelect()->joinLeft(
                array('alias_table' => $this->getTable('tgc_datamart/landing_media_code_alias')),
                'alias_table.media_code_id = main_table.entity_id'
            );
        }
        return $this;
    }

    /**
     * Add filter by alias
     *
     * @param int $alias
     * @return Tgc_Datamart_Model_Resource_EmailLanding_Mediacode_Collection
     */
    public function addAliasFilter($alias)
    {
        if (!is_array($alias)) {
            $alias = array($alias);
        }

        $this->addFilter('alias', array('in' => $alias), 'public');

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
     * Join aliases relation table if there is alias filter
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('alias')) {
            $this->joinAliases();
            $this->getSelect()->group('main_table.entity_id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }
}
