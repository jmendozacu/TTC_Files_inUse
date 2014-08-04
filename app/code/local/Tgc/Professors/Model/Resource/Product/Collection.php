<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * Adds filter by professor
     *
     * @param Tgc_Professors_Model_Professor|int $professor ID or porfessor's model
     * @return Tgc_Professors_Model_Resource_Product_Collection Self
     */
    public function addFilterByProfessor($professor)
    {
        $professorId = ($professor instanceof Tgc_Professors_Model_Professor) ? $professor->getId() : $professor;

        return $this->joinTable(
            'profs/product',
            'product_id=entity_id',
            array('professor_id' => 'professor_id'),
            array('professor_id' => $professorId)
        );
    }
}