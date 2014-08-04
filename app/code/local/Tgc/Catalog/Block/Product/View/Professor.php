<?php
/**
 * Tgc Catalog
 *
 * @author      Guidance Magento SuperTeam <magento@guidance.com>
 * @category    Tgc
 * @package     Catalog
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Catalog_Block_Product_View_Professor extends Mage_Catalog_Block_Product_View_Description
{
    /**
     * Returns professors that assotiated with given product
     *
     * @return Tgc_Professors_Model_Resource_Professor_Collection
     */
    public function getProfessors()
    {
        return Mage::getResourceModel('profs/professor_collection')
                       ->addProductToFilter($this->getProduct())
                       ->addAlmaMaterList()
                       ->addSchoolList();
    }
}
