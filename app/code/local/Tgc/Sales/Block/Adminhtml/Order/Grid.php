<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Tgc_Sales
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Sales_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{

    protected $_magentoDefaultSalesOrderColumns = array(
        'real_order_id',
        'store_id',
        'created_at',
        'billing_name',
        'shipping_name',
        'base_grand_total',
        'grand_total',
        'status'
    );

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        /**
         * An error was occuring because, since two tables are being joined on the sales orders grid, the table name needs to be included in the filter.
         * Otherwise, magento does not know which table in the join a field belongs to and will throw an error.  The join is created by xml in dax.xml
         * In that code, all of the fields have table names specified.  The code below adds table names to the fields (aka columns) that are in this grid
         * by default
         */
        foreach($this->getColumns() as $column) {
            $indexName = $column->getIndex();
            if(in_array($indexName, $this->_magentoDefaultSalesOrderColumns)) {
                $filterIndexName = 'main_table.' . $indexName;
                $column->setFilterIndex($filterIndexName);
            }
        }
    }
}