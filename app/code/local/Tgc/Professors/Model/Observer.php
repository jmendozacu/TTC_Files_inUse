<?php
/**
 * Professor model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Professors
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 *
 */
class Tgc_Professors_Model_Observer
{
    const BATCH_SIZE = 10000;

    private function _isSolrConfigured()
    {
        return Mage::getStoreConfig('catalog/search/engine') == 'enterprise_search/engine';
    }

    public function reindexAllInstitutions()
    {
        if (!$this->_isSolrConfigured()) {
            return;
        }
        $result = $this->_getInstitutionsData();
        $docs = array();
        $solrClient = $this->_getSolrClient();

        while ($institute = $result->fetch()) {
            $docs[] = $this->_getInstitutionSolrDocument($institute);
            if (count($docs) == self::BATCH_SIZE) {
                $this->_addDocumentsToSolr($solrClient, $docs);
                $docs = array();
            }
        }

        if (count($docs) > 0) {
            $this->_addDocumentsToSolr($solrClient, $docs);
        }
    }

    public function reindexAllProfessors()
    {
        if (!$this->_isSolrConfigured()) {
            return;
        }
        $result = $this->_getProfessorData();
        $docs = array();
        $solrClient = $this->_getSolrClient();

        while ($professor = $result->fetch()) {
            $docs[] = $this->_getProfessorSolrDocument($professor);
            if (count($docs) == self::BATCH_SIZE) {
                $this->_addDocumentsToSolr($solrClient, $docs);
                $docs = array();
            }
        }

        if (count($docs) > 0) {
            $this->_addDocumentsToSolr($solrClient, $docs);
        }
    }

    private function _getProfessorData()
    {
        $_db = Mage::getSingleton('core/resource')->getConnection('core_write');

        $select = $_db->select();
        $select->from(
            'professor',
            array('professor_id', 'first_name', 'last_name', 'title', 'qual')
        );

        return $_db->query($select);
    }

    private function _getInstitutionsData()
    {
        $_db = Mage::getSingleton('core/resource')->getConnection('core_write');

        $select = $_db->select();
        $select->from(
            $_db->getTableName('institution'),
            array('institution_id', 'name')
        );

        return $_db->query($select);
    }

    private function _getSolrClient($options = array())
    {
        $helper = Mage::helper('enterprise_search');
        $defOptions = array(
            'hostname' => $helper->getSolrConfigData('server_hostname'),
            'login'    => $helper->getSolrConfigData('server_username'),
            'password' => $helper->getSolrConfigData('server_password'),
            'port'     => $helper->getSolrConfigData('server_port'),
            'timeout'  => $helper->getSolrConfigData('server_timeout'),
            'path'     => $helper->getSolrConfigData('server_path'),
        );
        $options = array_merge($defOptions, $options);

        try {
            $client = Mage::getSingleton('enterprise_search/client_solr', $options);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $client;
    }

    private function _getProfessorSolrDocument($data)
    {
        $doc = new Apache_Solr_Document();

        $professorName = $data['title'] . ' ' . $data['first_name'] . ' ' . $data['last_name'] . ' ' . $data['qual'];

        $doc->addField('id', $data['professor_id']);
        $doc->addField('unique', $data['professor_id']);
        $doc->addField('professor_name_en', $professorName);
        //these are required fields in schema.xml that we won't use
        $doc->addField('visibility', 4);
        $doc->addField('in_stock', true);
        $doc->addField('store_id', 0);

        return $doc;
    }

    private function _getInstitutionSolrDocument($data)
    {
        $doc = new Apache_Solr_Document();

        $doc->addField('id', $data['institution_id']);
        $doc->addField('unique', $data['institution_id'].'-institution');
        $doc->addField('institution_en', $data['name']);
        //these are required fields in schema.xml that we won't use
        $doc->addField('visibility', 4);
        $doc->addField('in_stock', true);
        $doc->addField('store_id', -1);

        return $doc;
    }

    private function _addDocumentsToSolr(&$solrClient, $docs)
    {
        $solrClient->addDocuments($docs);
        $solrClient->commit();
    }

    private function _removeDocumentFromSolr(&$solrClient, $id)
    {
       $solrClient->deleteById($id);
       $solrClient->commit();
    }

    public function addProfessorToIndex($observer)
    {
        if (!$this->_isSolrConfigured()) {
            return;
        }
        $professor = $observer->getEvent()->getProfessor();
        $solrClient = $this->_getSolrClient();
        $data = array(
            'professor_id' => $professor->getProfessorId(),
            'first_name'   => $professor->getFirstName(),
            'last_name'    => $professor->getLastName(),
            'title'        => $professor->getTitle(),
            'qual'         => $professor->getQual(),
        );

        $docs= array(
            $this->_getProfessorSolrDocument($data)
        );

        $this->_addDocumentsToSolr($solrClient, $docs);
    }

    public function removeProfessorFromIndex($observer)
    {
        if (!$this->_isSolrConfigured()) {
            return;
        }
        $professor = $observer->getEvent()->getProfessor();
        $professorId = $professor->getId();
        $solrClient = $this->_getSolrClient();

        $this->_removeDocumentFromSolr($solrClient, $professorId);
    }

    public function addInstitutionToIndex($observer)
    {
        if (!$this->_isSolrConfigured()) {
            return;
        }
        $institution = $observer->getEvent()->getInstitution();
        $solrClient = $this->_getSolrClient();
        $data = array(
            'institution_id' => $institution->getInstitutionId(),
            'name'           => $institution->getName()
        );

        $docs = array(
            $this->_getInstitutionSolrDocument($data)
        );

        $this->_addDocumentsToSolr($solrClient, $docs);
    }

    public function removeInstitutionFromIndex($observer)
    {
        if (!$this->_isSolrConfigured()) {
            return;
        }
        $institution = $observer->getEvent()->getInstitution();
        $id = $institution->getId();
        $solrClient = $this->_getSolrClient();

        $this->_removeDocumentFromSolr($solrClient, $id.'-institution');
    }
}
