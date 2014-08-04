<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Model_Resource_Professor_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('profs/professor');
    }

    public function toOptionArray()
    {
        return array_map(
            function ($i) {
                $i['label'] .= ", {$i['f']} {$i['t']}";
                return $i;
            },
            $this->_toOptionArray('professor_id', 'last_name', array('f' => 'first_name', 't' => 'title'))
        );
    }

    public function addAlmaMaterList()
    {
        if (isset($this->_joinedTables['r'])) {
            return $this;
        }

        $this->getSelect()->joinLeft(array('r' => $this->getTable('profs/alma_mater')), 'main_table.professor_id=r.professor_id', null)
            ->joinLeft(array('i' => $this->getTable('profs/institution')), 'r.institution_id=i.institution_id', array());
        $this->_joinedTables['r'] = true;
        $this->getSelect()
            ->columns('*')
            ->columns(array('alma_mater_list' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT i.name SEPARATOR ", ")')))
            ->group('main_table.professor_id');

        return $this;
    }

    public function addSchoolList()
    {
        if (isset($this->_joinedTables['t'])) {
            return $this;
        }

        $this->getSelect()->joinLeft(array('t' => $this->getTable('profs/teaching')), 'main_table.professor_id=t.professor_id', null)
            ->joinLeft(array('it' => $this->getTable('profs/institution')), 't.institution_id=it.institution_id', array());
        $this->_joinedTables['t'] = true;
        $this->getSelect()
            ->columns('*')
            ->columns(array('school_list' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT it.name SEPARATOR ", ")')))
            ->group('main_table.professor_id');

        return $this;
    }

    public function addProductToFilter($product)
    {
        $productId = $product instanceof Mage_Catalog_Model_Product ? $product->getId() : $product;
        $this->join(array('products' => 'profs/product'), 'products.professor_id=main_table.professor_id', array());
        $this->getSelect()->group('main_table.professor_id');

        return $this->addFieldToFilter('product_id', $productId);
    }
}