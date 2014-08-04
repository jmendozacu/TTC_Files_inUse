<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Resource_Order_Item_Collection extends Mage_Sales_Model_Resource_Order_Item_Collection
{
    private $_taxesJoined = false;

    protected $_orderField = 'main_table.order_id';

    public function addTaxInfo()
    {
        if (!$this->_taxesJoined) {
            $this->getSelect()
                ->joinLeft(
                    array('ti' => $this->getTable('tax/sales_order_tax_item')),
                    'main_table.item_id = ti.item_id',
                    'tax_percent'
                )
                ->joinLeft(
                    array('t' => $this->getTable('sales/order_tax')),
                    'ti.tax_id = t.tax_id',
                    array('tax_code' => 'code', 'tax_title' => 'title')
                );
        }

        return $this;
    }
}