<?php
/**
 * Solr search
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Solr
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Solr_Model_Resource_Engine extends Mana_Filters_Resource_Solr_Engine
{
    private $_resource;
    private $_writeAdapter;

    /**
     * Retrieve results for search request
     *
     * @param  string $query
     * @param  array  $params
     * @return array
     */
    public function getAutoCompleteRequest($query, $params = array())
    {
        return $this->_adapter->getAutoCompleteRequest($query, $params);
    }

    /**
     * @return Mage_Core_Model_Resource
     */
    private function _getResource()
    {
        if (isset($this->_resource)) {
            return $this->_resource;
        }

        $this->_resource = Mage::getSingleton('core/resource');

        return $this->_resource;
    }

    /**
     * @return Varien_Db_Adapter_Interface
     */
    private function _getWriteAdapter()
    {
        if (isset($this->_writeAdapter)) {
            return $this->_writeAdapter;
        }

        $this->_writeAdapter = $this->_getResource()->getConnection('core_write');

        return $this->_writeAdapter;
    }

    public function prepareEntityIndex($index, $separator = null)
    {
        if (!isset($index['professor'])) {
            return $index;
        }

        foreach ($index['professor'] as $productId => $professorIds) {
            $professors = $this->_getProfessorsFromIds($professorIds);
            if (empty($professors)) {
                unset($index['professor'][$productId]);
            } else {
                $index['professor'][$productId] = $professors;

                $institutions = array();
                $teachingInstitutions = $this->_getProfessorTeachingData($professorIds);
                if (!empty($teachingInstitutions)) {
                    $index['professor_teaching'][$productId] = implode(', ', $teachingInstitutions);
                    $institutions = $teachingInstitutions;
                }

                $almaMater = $this->_getProfessorAlmaMaterData($professorIds);
                if (!empty($almaMater)) {
                    $index['professor_alma_mater'][$productId] = implode(', ', $almaMater);
                    $institutions = array_merge($institutions, $almaMater);
                }

                if ($institutions) {
                    $institutions = array_unique($institutions);
                    $index['institution'][$productId] = implode(', ', $institutions);
                }
            }
        }

        return $index;
    }

    private function _getProfessorsFromIds($professorIds)
    {
        $adapter  = $this->_getWriteAdapter();
        $select   = $adapter->select();
        $resource = $this->_getResource();
        $ids = explode(',', $professorIds);

        $select->from(
            array('professor' => $resource->getTableName('professor')),
            array(
                'names' => new Zend_Db_Expr(
                    'CONCAT_WS(" ", title, first_name, last_name, qual)'
                )
            )
        )
        ->where('professor_id IN (?)', $ids);

        $result = (array)$adapter->fetchCol($select);

        return empty($result) ? false : implode(', ', $result);
    }

    private function _getProfessorTeachingData($professorIds)
    {
        $adapter  = $this->_getWriteAdapter();
        $select   = $adapter->select();
        $resource = $this->_getResource();

        $select->from(
            array('teaching' => $resource->getTableName('professor_teaching')),
            array()
        )
        ->joinInner(
            array('inst' => $resource->getTableName('institution')),
            'teaching.institution_id = inst.institution_id',
            array('institution_name' => 'inst.name')
        )
        ->where('teaching.professor_id IN (?)', $professorIds);

        $result = (array)$adapter->fetchCol($select);

        return empty($result) ? false : $result;
    }

    private function _getProfessorAlmaMaterData($professorIds)
    {
        $adapter  = $this->_getWriteAdapter();
        $select   = $adapter->select();
        $resource = $this->_getResource();

        $select->from(
            array('am' => $resource->getTableName('professor_alma_mater')),
            array()
        )
        ->joinInner(
            array('inst' => $resource->getTableName('institution')),
            'am.institution_id = inst.institution_id',
            array('institution_name' => 'inst.name')
        )
        ->where('am.professor_id IN (?)', $professorIds);

        $result = (array)$adapter->fetchCol($select);

        return empty($result) ? false : $result;
    }

    /**
     * Public method for adapter
     *
     * @return Enterprise_Search_Model_Adapter_Abstract
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }
}
