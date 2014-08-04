<?php


class Tgc_Dax_Model_Import_Entity_Product_Type_Simple
    extends Mage_ImportExport_Model_Import_Entity_Product_Type_Simple
{

    /**
     * Save simple product type specific data.
     * This saves the professor_information csv field
     *
     * @return Mage_ImportExport_Model_Import_Entity_Product_Type_Abstract
     */
    public function saveData()
    {
        if($this->_entityModel->getEntityTypeCode() == Tgc_Dax_Model_Import_Entity_Course::ENTITY_TYPE_CODE
            || $this->_entityModel->getEntityTypeCode() == Tgc_Dax_Model_Import_Entity_Set::ENTITY_TYPE_CODE) {
            $allAssociatedProfessors = array();
            $connection = $this->_entityModel->getConnection();
            $allSku = $connection->fetchPairs("SELECT sku, entity_id FROM catalog_product_entity");

            while ($bunch = $this->_entityModel->getNextBunch()) {
                foreach ($bunch as $rowNum => $rowData) {
                    if (!$this->_entityModel->isRowAllowedToImport($rowData, $rowNum)) {
                        continue;
                    }

                    //code inside if clause, adds data to an array containing all professors associated with a course.
                    if($rowData['professor_information']) {
                        $productId = $allSku[$rowData['sku']];
                        if($productId) { //the product id should always exist, this is just a safety measure.
                            $this->_addAssociatedProfessors($rowData['professor_information'], $productId, $allAssociatedProfessors);
                        }
                    }
                }
            }

            $this->_saveAssociatedProfessors($allAssociatedProfessors);
        }

        return $this;
    }

    protected function _addAssociatedProfessors($associatedProfessor, $productId, &$allAssociatedProfessors)
    {
        $validAssociatedProfessorsArray = $this->_entityModel->retrieveValidProfessorsOnly($associatedProfessor);

        //associated profesors are only saved, if at least one professor is valid.
        if($validAssociatedProfessorsArray) {
            $allAssociatedProfessors[$productId] = $validAssociatedProfessorsArray;
        }
    }

    protected function _saveAssociatedProfessors($allAssociatedProfessors)
    {
        if(count($allAssociatedProfessors) > 0) {
            foreach($allAssociatedProfessors as $productId => $associatedProfessorIds) {
                Mage::getResourceModel('profs/professor')->linkProfessorsToProduct($productId, $associatedProfessorIds);
            }
        }
    }

    public function addNewCustomAttributeOptions($attrCode, $attrValue, $intOptionId)
    {
        $this->_attributes[Tgc_Dax_Model_Import_Entity_Course::ATTRIBUTE_SET_NAME][$attrCode]['options'][$attrValue] = $intOptionId;
        $this->_attributes[Tgc_Dax_Model_Import_Entity_Set::PROFILE_ATTRIBUTE_SET][$attrCode]['options'][$attrValue] = $intOptionId;
    }
}