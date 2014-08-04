<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Model_Resource_Professor extends Mage_Core_Model_Resource_Db_Abstract
{
    const COL_PROFESSOR_ID   = 'professor_id';
    const COL_INSTITUTION_ID = 'institution_id';
    const COL_PRODUCT_ID     = 'product_id';

    private $_tableAlmaMater;
    private $_tableProduct;
    private $_tableTeaching;
    private $_tableInstitution;
    private $_professors = array();

    protected function _construct()
    {
        $this->_init('profs/professor', 'professor_id');
        $this->_tableAlmaMater   = $this->getTable('profs/alma_mater');
        $this->_tableProduct     = $this->getTable('profs/product');
        $this->_tableTeaching    = $this->getTable('profs/teaching');
        $this->_tableInstitution = $this->getTable('profs/institution');
    }

    public function getAlmaMaterIds($professorId)
    {
        return $this->_getRelation(
            $this->_tableAlmaMater,
            self::COL_PROFESSOR_ID,
            $professorId,
            self::COL_INSTITUTION_ID
        );
    }

    public function saveAlmaMaterIds($professorId, array $ids)
    {
        $this->_updateRelation(
            $this->_tableAlmaMater,
            self::COL_PROFESSOR_ID, $professorId,
            self::COL_INSTITUTION_ID, $ids
        );

        return $this;
    }

    public function getTeachingAtIds($professorId)
    {
        return $this->_getRelation(
            $this->_tableTeaching,
            self::COL_PROFESSOR_ID,
            $professorId,
            self::COL_INSTITUTION_ID
        );
    }

    public function saveTeachingAtIds($professorId, array $ids)
    {
        $this->_updateRelation(
            $this->_tableTeaching,
            self::COL_PROFESSOR_ID,
            $professorId,
            self::COL_INSTITUTION_ID,
            $ids
        );

        return $this;
    }

    public function getProductIds($professorId)
    {
        return $this->_getRelation(
            $this->_tableProduct,
            self::COL_PROFESSOR_ID,
            $professorId,
            self::COL_PRODUCT_ID
        );
    }

    public function saveProductIds($professorId, array $ids)
    {
        $this->_updateRelation(
            $this->_tableProduct,
            self::COL_PROFESSOR_ID, $professorId,
            self::COL_PRODUCT_ID, $ids
        );

        return $this;
    }

    public function linkProfessorsToProduct($productId, array $professorIds)
    {
        $this->_updateRelation(
            $this->_tableProduct,
            self::COL_PRODUCT_ID, $productId,
            self::COL_PROFESSOR_ID, $professorIds
        );

        return $this;
    }

    private function _prepareRows($primaryColName, $primaryId, $columnName, array $ids)
    {
        return array_map(
            function ($id) use ($primaryColName, $primaryId, $columnName) {
                return array($primaryColName => $primaryId, $columnName => $id);
            },
            $ids
        );
    }

    private function _updateRelation($table, $primaryColName, $primaryId, $columnName, array $ids)
    {
        $conn = $this->_getWriteAdapter();
        $conn->delete($table, array("$primaryColName = ?" => $primaryId));

        if (count($ids)) {
            $conn->insertMultiple($table, $this->_prepareRows($primaryColName, $primaryId, $columnName, $ids));
        }
    }

    private function _getRelation($table, $primaryColName, $primaryId, $columnName)
    {
        $select = $this->_getReadAdapter()
            ->select()
            ->from($table)
            ->where("$primaryColName = ?", $primaryId);

        return array_map(
            function ($r) use ($columnName) {
                return $r[$columnName];
            },
            $this->_getReadAdapter()->fetchAll($select)
        );
    }

    public function getProfessorsForProduct(Mage_Catalog_Model_Product $product)
    {
        if (isset($this->_professors[$product->getId()])) {
            return $this->_professors[$product->getId()];
        }

        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from(array('profs' => $this->getMainTable()),
                array(
                    'name'  => new Zend_Db_Expr('CONCAT_WS(" ", title, first_name, last_name, qual)'),
                    'bio', 'professor_id', 'photo'
                )
            )
            ->joinLeft(
                array('pt' => $this->_tableTeaching),
                '(pt.professor_id = profs.professor_id)',
                array()
            )
            ->joinLeft(
                array('am' => $this->_tableAlmaMater),
                '(am.professor_id = profs.professor_id)',
                array()
            )
            ->joinLeft(
                array('product' => $this->_tableProduct),
                '(product.professor_id = profs.professor_id)',
                array()
            )
            ->joinLeft(
                array('ti' => $this->_tableInstitution),
                '(ti.institution_id = pt.institution_id)',
                array('teaching' => 'ti.name')
            )
            ->joinLeft(
                array('ai' => $this->_tableInstitution),
                '(ai.institution_id = am.institution_id)',
                array('alma_mater' => 'ai.name')
            )
            ->joinLeft(
                array('pproducts' => $this->_tableProduct),
                '(pproducts.professor_id = profs.professor_id)',
                array('products' => new Zend_Db_Expr('GROUP_CONCAT(pproducts.product_id ORDER BY pproducts.product_id ASC)'))
            )
            ->where('product.product_id = :productId')
            ->group('professor_id');

        $bind = array(
            ':productId'  => (int)$product->getId(),
        );

        $this->_professors[$product->getId()] = (array)$adapter->fetchAll($select, $bind);

        return $this->_professors[$product->getId()];
    }

    /**
     * Retrieve professor ids for products
     *
     * @param array $productIds
     * @return array
     */
    public function getProfessorIdsByProducts($productIds)
    {
        $adapter = $this->_getReadAdapter();
        $dataRows = $adapter->fetchAll(
            $adapter->select()
                ->from($this->getTable('profs/product'))
                ->where('product_id IN (?)', $productIds)
        );

        $productToProfessorsMap = array();
        foreach ($dataRows as $row) {
            if (!isset($productToProfessorsMap[$row['product_id']])) {
                $productToProfessorsMap[$row['product_id']] = array();
            }
            $productToProfessorsMap[$row['product_id']][] = $row['professor_id'];
        }

        return $productToProfessorsMap;
    }
}
