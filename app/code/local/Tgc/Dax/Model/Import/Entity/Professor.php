<?php
/**
 * Professor import model
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Dax_Model_Import_Entity_Professor extends Tgc_Dax_Model_Import_Entity_Checksum_Base
{
    /**
     * Progessors table name
     *
     * @var string
     */
    protected $_professorsTable;

    /**
     * Progessors table id field name
     *
     * @var string
     */
    protected $_professorsTableIdFieldName = 'professor_id';

    /**
     * Institutions table name
     *
     * @var string
     */
    protected $_institutionsTable;

    /**
     * Institutions table id field name
     *
     * @var string
     */
    protected $_institutionsTableIdFieldName = 'institution_id';

    /**
     * Institutions table name field name
     *
     * @var string
     */
    protected $_institutionsTableNameFieldName = 'name';

    /**
     * Array of existing professor ids
     *
     * @var array
     */
    protected $_professorIds = array();

    /**
     * Map between import row number and professor id
     *
     * @var array
     */
    protected $_rowNumProfessorId = array();

    /**
     * Categories text-path to ID hash with roots checking.
     *
     * @var array
     */
    protected $_categoriesWithRoots = array();

    /**
     * Categories text-path to ID hash.
     *
     * @var array
     */
    protected $_categories = array();

    /**
     * Institutions name to Id hash
     *
     * @var array
     */
    protected $_institutions = array();

    /**
     * Photo files uploader
     *
     * @var Mage_ImportExport_Model_Import_Uploader
     */
    protected $_fileUploader;

    /**
     * Import file column names
     */
    const COL_ID             = 'professor_id';
    const COL_FIRST_NAME     = 'firstname';
    const COL_LAST_NAME      = 'lastname';
    const COL_QUAL           = 'qual';
    const COL_BIO            = 'bio';
    const COL_RANK           = 'rank';
    const COL_QUOTE          = 'quote';
    const COL_TITLE          = 'title';
    const COL_ROOT_CATEGORY  = 'root_category';
    const COL_CATEGORY       = 'category';
    const COL_INSTITUTION    = 'institution';
    const COL_ALMAMATER      = 'almamater';
    const COL_EMAIL          = 'email';
    const COL_FACEBOOK       = 'facebook';
    const COL_TWITTER        = 'twitter';
    const COL_PINTEREST      = 'pinterest';
    const COL_YOUTUBE        = 'youtube';
    const COL_PHOTO          = 'photo';
    const COL_TESTIMONIAL    = 'testimonial';

    protected $_professorFieldsToColMap = array(
        'first_name'  => self::COL_FIRST_NAME,
        'last_name'   => self::COL_LAST_NAME,
        'qual'        => self::COL_QUAL,
        'bio'         => self::COL_BIO,
        'rank'        => self::COL_RANK,
        'quote'       => self::COL_QUOTE,
        'title'       => self::COL_TITLE,
        'email'       => self::COL_EMAIL,
        'facebook'    => self::COL_FACEBOOK,
        'twitter'     => self::COL_TWITTER,
        'pinterest'   => self::COL_PINTEREST,
        'youtube'     => self::COL_YOUTUBE,
        'photo'       => self::COL_PHOTO
    );

    /**
     * Error - invalid category
     */
    const ERROR_INVALID_CATEGORY = 'invalidCategory';

    /**
     * Error - id required
     */
    const ERROR_ID_REQUIRED      = 'idRequired';

    /**
     * Error - invalid id
     */
    const ERROR_INVALID_ID       = 'invalidId';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::ERROR_INVALID_CATEGORY => 'Category does not exists',
        self::ERROR_ID_REQUIRED      => 'Professor Id is required',
        self::ERROR_INVALID_ID       => 'Professor Id must be a positive integer'
    );

    /**
     * Permanent entity columns.
     *
     * @var array
     */
    protected $_permanentAttributes = array(self::COL_ID);

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->_dataSourceModel = Mage_ImportExport_Model_Import::getDataSourceModel();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
        $this->_connection      = Mage::getSingleton('core/resource')->getConnection('write');

        $this->_initProfessorIds()
            ->_initCategories()
            ->_initInstitutions();
    }

    /**
     * Retrieve existing professor ids
     *
     * @return \Tgc_Dax_Model_Import_Entity_Professor
     */
    protected function _initProfessorIds()
    {
        $this->_professorIds = $this->_connection->fetchCol(
            $this->_connection->select()->from(
                $this->_getProfessorsTable(),
                array($this->_professorsTableIdFieldName)
            )
        );
        return $this;
    }

    /**
     * Initialize categories text-path to ID hash.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Product
     */
    protected function _initCategories()
    {
        $collection = Mage::getResourceModel('catalog/category_collection')->addNameToResult();
        /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
        foreach ($collection as $category) {
            $structure = explode('/', $category->getPath());
            $pathSize  = count($structure);
            if ($pathSize > 1) {
                $path = array();
                for ($i = 1; $i < $pathSize; $i++) {
                    $path[] = $collection->getItemById($structure[$i])->getName();
                }
                $rootCategoryName = array_shift($path);
                if (!isset($this->_categoriesWithRoots[$rootCategoryName])) {
                    $this->_categoriesWithRoots[$rootCategoryName] = array();
                }
                $index = implode('/', $path);
                $this->_categoriesWithRoots[$rootCategoryName][$index] = $category->getId();
                if ($pathSize > 2) {
                    $this->_categories[$index] = $category->getId();
                }
            }
        }
        return $this;
    }

    /**
     * Retrieve existing institutions
     *
     * @return \Tgc_Dax_Model_Import_Entity_Professor
     */
    protected function _initInstitutions()
    {
        $this->_institutions = $this->_connection->fetchPairs(
            $this->_connection->select()->from(
                $this->_getInstitutionsTable(),
                array($this->_institutionsTableNameFieldName, $this->_institutionsTableIdFieldName)
            )
        );
        return $this;
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'professor';
    }

    /**
     * Validate data row.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return boolean
     */
    public function validateRow(array $rowData, $rowNum)
    {
        return $this->_isProfessorIdValid($rowData, $rowNum) && $this->_isProfessorCategoryValid($rowData, $rowNum);
    }

    /**
     * Check professor id validity
     *
     * @param array $rowData
     * @param type $rowNum
     * @return boolean
     */
    protected function _isProfessorIdValid(array $rowData, $rowNum)
    {
        $emptyId = empty($rowData[self::COL_ID]);
        if ($emptyId && Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->addRowError(self::ERROR_ID_REQUIRED, $rowNum);
            return false;
        }

        if (!$emptyId
            && !((string)(int)$rowData[self::COL_ID] == trim($rowData[self::COL_ID]) && (int)$rowData[self::COL_ID] > 0)) {
            $this->addRowError(self::ERROR_INVALID_ID, $rowNum);
            return false;
        }

        return true;
    }

    /**
     * Check professor category validity.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    protected function _isProfessorCategoryValid(array $rowData, $rowNum)
    {
        $emptyCategory = empty($rowData[self::COL_CATEGORY]);
        $emptyRootCategory = empty($rowData[self::COL_ROOT_CATEGORY]);
        $hasCategory = $emptyCategory ? false : isset($this->_categories[$rowData[self::COL_CATEGORY]]);
        $category = $emptyRootCategory ? null : $this->_categoriesWithRoots[$rowData[self::COL_ROOT_CATEGORY]];
        if (!$emptyCategory && !$hasCategory
            || !$emptyRootCategory && !isset($category)
            || !$emptyRootCategory && !$emptyCategory && !isset($category[$rowData[self::COL_CATEGORY]])
        ) {
            $this->addRowError(self::ERROR_INVALID_CATEGORY, $rowNum);
            return false;
        }
        return true;
    }

    /**
     * Import data rows.
     *
     * @return boolean
     */
    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->_deleteProfessors();
        } else {
            $this->_saveProfessors();
        }

        if (Mage::getStoreConfig('catalog/search/engine') == 'enterprise_search/engine') {
            $indexProcess = Mage::getSingleton('index/indexer')->getProcessByCode('catalogsearch_fulltext');
            if ($indexProcess) {
                $indexProcess->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
            }
        }

        return true;
    }

    /**
     * Save professors data
     *
     * @return \Tgc_Dax_Model_Import_Entity_Professor
     */
    protected function _saveProfessors()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityRowsIn   = array();
            $entityRowsUp   = array();
            $institutionsIn = array();
            $institutions   = array();
            $almamaters     = array();
            $uploadedPhotoFiles = array();

            foreach ($bunch as $rowNum => $rowData) {
                //$this->_filterRowData($rowData);
                if (!$this->validateRow($rowData, $rowNum)) {
                    continue;
                }



                if (!empty($rowData[self::COL_PHOTO])) {
                    if (!array_key_exists($rowData[self::COL_PHOTO], $uploadedPhotoFiles)) {
                        $uploadedPhotoFiles[$rowData[self::COL_PHOTO]] = $this->_uploadPhotoFiles(
                            $rowData[self::COL_PHOTO]
                        );
                    }
                    $rowData[self::COL_PHOTO] = $uploadedPhotoFiles[$rowData[self::COL_PHOTO]];
                }

                $professorEntityData = array();
                if (!empty($rowData[self::COL_ID])) {
                    $professorEntityData[$this->_professorsTableIdFieldName] = (int)$rowData[self::COL_ID];
                }
                foreach ($this->_professorFieldsToColMap as $field => $col) {
                    if (array_key_exists($col, $rowData)) {
                        $professorEntityData[$field] = $rowData[$col];
                    }
                }

                $categoryPath = empty($rowData[self::COL_CATEGORY]) ? '' : $rowData[self::COL_CATEGORY];
                if (!empty($rowData[self::COL_ROOT_CATEGORY])) {
                    $categoryId = $this->_categoriesWithRoots[$rowData[self::COL_ROOT_CATEGORY]][$categoryPath];
                    $professorEntityData['category_id'] = $categoryId;
                } elseif (!empty($categoryPath)) {
                    $professorEntityData['category_id'] = $this->_categories[$categoryPath];
                } elseif (array_key_exists(self::COL_CATEGORY, $rowData)) {
                    $professorEntityData['category_id'] = null;
                }

                if ($professorEntityData[$this->_professorsTableIdFieldName]
                    && in_array($professorEntityData[$this->_professorsTableIdFieldName], $this->_professorIds)) {
                    $this->_rowNumProfessorId[$rowNum] = $professorEntityData[$this->_professorsTableIdFieldName];
                    $entityRowsUp[] = $professorEntityData;
                } else {
                    $professorEntityData['import_row_num'] = $rowNum;
                    $entityRowsIn[$rowNum] = $professorEntityData;
                }

                if (!empty($rowData[self::COL_INSTITUTION])) {
                    $rowInstitution = explode('|', $rowData[self::COL_INSTITUTION]);
                    foreach ($rowInstitution as $institutionName) {
                        $institutions[$rowNum][$institutionName] = true;
                        if (!array_key_exists($institutionName, $this->_institutions)) {
                            $institutionsIn[$institutionName] = array(
                                $this->_institutionsTableNameFieldName => $institutionName
                            );
                        }
                    }
                }

                if (!empty($rowData[self::COL_ALMAMATER])) {
                    $rowAlmamaters = explode('|', $rowData[self::COL_ALMAMATER]);
                    foreach ($rowAlmamaters as $institutionName) {
                        $almamaters[$rowNum][$institutionName] = true;
                        if (!array_key_exists($institutionName, $this->_institutions)) {
                            $institutionsIn[$institutionName] = array(
                                $this->_institutionsTableNameFieldName => $institutionName
                            );
                        }
                    }
                }
            }

            $this->_saveProfessorEntities($entityRowsIn, $entityRowsUp)
                ->_saveInstitutions($institutionsIn)
                ->_saveProfessorInstitutions(
                    Mage::getSingleton('core/resource')->getTableName('profs/teaching'),
                    $institutions
                )
                ->_saveProfessorInstitutions(
                    Mage::getSingleton('core/resource')->getTableName('profs/alma_mater'),
                    $almamaters
                );
        }

        return $this;
    }

    /**
     * Save main entity data
     *
     * @param array $entityRowsIn
     * @param array $entityRowsUp
     * @return \Tgc_Dax_Model_Import_Entity_Professor
     */
    protected function _saveProfessorEntities(array $entityRowsIn, array $entityRowsUp)
    {
        if ($entityRowsUp) {
            $this->_connection->insertOnDuplicate($this->_getProfessorsTable(), $entityRowsUp);
        }

        if ($entityRowsIn) {
            $this->_connection->insertMultiple($this->_getProfessorsTable(), $entityRowsIn);

            $newIds = $this->_connection->fetchPairs(
                $this->_connection->select()
                    ->from($this->_getProfessorsTable(), array('import_row_num', $this->_professorsTableIdFieldName))
                    ->where('import_row_num IN (?)', array_keys($entityRowsIn))
            );
            foreach ($newIds as $rowNum => $id) {
                $this->_rowNumProfessorId[$rowNum] = $id;
            }

            $this->_connection->update(
                $this->_getProfessorsTable(),
                array('import_row_num' => null),
                array('`' . $this->_professorsTableIdFieldName . '` IN (?)' => $newIds)
            );
        }

        return $this;
    }

    /**
     * Save new institutions
     *
     * @param array $institutions
     */
    protected function _saveInstitutions(array $institutions)
    {
        if ($institutions) {
            $this->_connection->insertMultiple($this->_getInstitutionsTable(), $institutions);

            $newIds = $this->_connection->fetchPairs(
                $this->_connection->select()
                    ->from(
                        $this->_getInstitutionsTable(),
                        array($this->_institutionsTableNameFieldName, $this->_institutionsTableIdFieldName)
                    )
                    ->where('`' . $this->_institutionsTableNameFieldName . '` IN (?)', $institutions)
            );
            foreach ($newIds as $institutionName => $institutionId) {
                $this->_institutions[$institutionName] = $institutionId;
            }
        }

        return $this;
    }

    /**
     * Save professor institutions assignment data
     *
     * @param array $institutions
     * @return \Tgc_Dax_Model_Import_Entity_Professor
     */
    protected function _saveProfessorInstitutions($tableName, array $institutionsData)
    {
        if ($institutionsData) {
            $institutionsIn  = array();
            $professorIdsDel = array();

            foreach ($institutionsData as $rowNum => $institutions) {
                $professorId = isset($this->_rowNumProfessorId[$rowNum]) ? $this->_rowNumProfessorId[$rowNum] : false;
                if (!$professorId) {
                    continue;
                }
                $professorIdsDel[] = $professorId;

                foreach (array_keys($institutions) as $institutionName) {
                    $institutionId = isset($this->_institutions[$institutionName])
                        ? $this->_institutions[$institutionName] : false;
                    if (!$institutionId) {
                        continue;
                    }
                    $institutionsIn[] = array('professor_id' => $professorId, 'institution_id' => $institutionId);
                }
            }

            //if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND != $this->getBehavior()) {
                $this->_connection->delete(
                    $tableName,
                    $this->_connection->quoteInto('professor_id IN (?)', $professorIdsDel)
                );
            //}
            if ($institutionsIn) {
                $this->_connection->insertOnDuplicate(
                    $tableName,
                    $institutionsIn,
                    array('professor_id', 'institution_id')
                );
            }
        }

        return $this;
    }

    /**
     * Delete professors
     *
     * @return \Tgc_Dax_Model_Import_Entity_Professor
     */
    protected function _deleteProfessors()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idToDelete = array();

            foreach ($bunch as $rowData) {
                $idToDelete[] = $rowData[self::COL_ID];
            }

            if ($idToDelete) {
                $this->_connection->delete(
                    $this->_getProfessorsTable(),
                    $this->_connection->quoteInto('`' . $this->_professorsTableIdFieldName . '` IN (?)', $idToDelete)
                );
            }
        }
        return $this;
    }

    /**
     * Retrieve professors table name
     *
     * @return string
     */
    protected function _getProfessorsTable()
    {
        if (is_null($this->_professorsTable)) {
            $this->_professorsTable = Mage::getResourceModel('profs/professor')->getMainTable();
        }

        return $this->_professorsTable;
    }

    /**
     * Rettrieve institutions table name
     *
     * @return string
     */
    protected function _getInstitutionsTable()
    {
        if (is_null($this->_institutionsTable)) {
            $this->_institutionsTable = Mage::getResourceModel('profs/institution')->getMainTable();
        }

        return $this->_institutionsTable;
    }

    /**
     * Returns an object for upload a media files
     */
    protected function _getUploader()
    {
        if (is_null($this->_fileUploader)) {
            $this->_fileUploader    = new Mage_ImportExport_Model_Import_Uploader();

            $this->_fileUploader->init();

            $tmpDir     = Mage::getConfig()->getOptions()->getMediaDir() . DS . 'import';
            $destDir    = Mage::getConfig()->getOptions()->getMediaDir() . DS . Tgc_Professors_Helper_Image::MEDIA_PATH;
            if (!is_writable($destDir)) {
                @mkdir($destDir, 0777, true);
            }
            if (!$this->_fileUploader->setTmpDir($tmpDir)) {
                Mage::throwException("File directory '{$tmpDir}' is not readable.");
            }
            if (!$this->_fileUploader->setDestDir($destDir)) {
                Mage::throwException("File directory '{$destDir}' is not writable.");
            }
        }
        return $this->_fileUploader;
    }

    /**
     * Uploading files into the "professor" media folder.
     * Return a new file name if the same file is already exists.
     *
     * @param string $fileName
     * @return string
     */
    protected function _uploadPhotoFiles($fileName)
    {
        try {
            $res = $this->_getUploader()->move($fileName);
            return $res['file'];
        } catch (Exception $e) {
            return '';
        }
    }
}
