<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Dax_Model_Importcron extends Enterprise_ImportExport_Model_Import
{
    const CHECKSUM_IDENTIFIER = 'Expected Records';

    const EMAIL_TITLE_DEFAULT = 'default';

    const EMAIL_TITLE_TOTALLY_INVALID = 'totallyinvalid';

    const EMAIL_TITLE_ERROR_LIMIT_REACHED = 'errorlimitreached';

    const EMAIL_TITLE_NOTALLOWED = 'notallowed';

    const EMAIL_TITLE_PARTIALLY_VALID = 'partiallyvalid';

    const EMAIL_TITLE_CHECKSUM_INVALID = 'checksuminvalid';

    const EMAIL_TITLE_ALLROWSIMPORTED_BUT_NOT_ALLFIELDS = 'allrowsimportednotallfields';

    const EMAIL_TITLE_ONE_OR_MORE_ROWS_INVALID = 'oneormorerowsinvalid';

    const EMAIL_TITLE_FILE_INVALID = 'fileinvalid';

    protected $_emailTitles = array(
        self::EMAIL_TITLE_DEFAULT               => 'Summary',
        self::EMAIL_TITLE_TOTALLY_INVALID       => 'Failed: No Rows Imported. None of The Rows are Valid',
        self::EMAIL_TITLE_NOTALLOWED            => 'Failed: No Rows Imported. Import is Not Allowed',
        self::EMAIL_TITLE_ERROR_LIMIT_REACHED   => 'Failed: No Rows Imported. Error Limit Reached',
        self::EMAIL_TITLE_CHECKSUM_INVALID      => 'Failed: No Rows Imported. The checksum is invalid or does not exist.',
        self::EMAIL_TITLE_PARTIALLY_VALID       => 'Result: Some rows were not imported',
        self::EMAIL_TITLE_ALLROWSIMPORTED_BUT_NOT_ALLFIELDS => 'Result: All rows imported, some field(s) were not imported',
        self::EMAIL_TITLE_FILE_INVALID          => 'Failed: The import file is missing, it cannot be read, or the connection failed',
        self::EMAIL_TITLE_ONE_OR_MORE_ROWS_INVALID => 'Result: Partial Failure, Some Rows Could Not be Imported',
    );

    private $_isUsingInterface;

    private $_reformatEntityFiles = array('customer');

    /**
     * Validates source file and returns validation result.
     *
     * @param string $sourceFile Full path to source file
     * @return bool
     */
    public function validateSource($sourceFile)
    {
        $result = parent::validateSource($sourceFile);

        if(!$this->getIsChecksumValidValue()) {
           $result = false;
        }

        return $result;
    }

    public function getOperationResultMessages($validationResult)
    {
        $messages = parent::getOperationResultMessages($validationResult);

        $isCheckSumValid = true;
        if(!$this->validateChecksum()) {
            $messages[] = Mage::helper('importexport')->__('Checksum value does not match the row count. Import is not possible');
            $isCheckSumValid = false;
        }
        $this->setIsChecksumValidValue($isCheckSumValid);

        return $messages;
    }

    /**
     * Run import through cron
     *
     * @param Enterprise_ImportExport_Model_Scheduled_Operation $operation
     * @return bool
     */
    public function runSchedule(Enterprise_ImportExport_Model_Scheduled_Operation $operation)
    {
        $sourceFile = $operation->getFileSource($this);
        $result = $sourceFile && $this->validateSource($sourceFile);
        $isAllowedForcedImport = $operation->getForceImport()
            && $this->getProcessedRowsCount() != $this->getInvalidRowsCount();
        if (($isAllowedForcedImport || $result) && $this->getIsChecksumValidValue()) {
            $result = $this->importSource();
        }
        if ($result) {
            $this->reindexAll();
        }
        return (bool)$result;
    }

    /**
     * The title is influenced by the types of errors that occur.
     * @param $result
     */
    public function getEmailTitle($result)
    {
        $title = self::EMAIL_TITLE_DEFAULT;
        if ($this->getProcessedRowsCount()) {
            if(!$this->validateChecksum()) {
                $title = self::EMAIL_TITLE_CHECKSUM_INVALID;
            } elseif (!$result) {
                if ($this->getProcessedRowsCount() == $this->getInvalidRowsCount()) {
                    $title = self::EMAIL_TITLE_TOTALLY_INVALID;
                } elseif ($this->getErrorsCount() >= $this->getErrorsLimit()) {
                    $title = self::EMAIL_TITLE_ERROR_LIMIT_REACHED;
                } else {
                    if ($this->isImportAllowed()) {
                        $title = self::EMAIL_TITLE_PARTIALLY_VALID;
                    } else {
                        $title = self::EMAIL_TITLE_NOTALLOWED;
                    }
                }
            } elseif($result && $this->getErrorsCount()) {
                if(!$this->getInvalidRowsCount()) { //runs if no rows were invalid.
                    $title = self::EMAIL_TITLE_ALLROWSIMPORTED_BUT_NOT_ALLFIELDS;
                } else { //clause executes if one or more rows is invalid.
                    $title = self::EMAIL_TITLE_ONE_OR_MORE_ROWS_INVALID;
                }
            }
        } else {
            $title = self::EMAIL_TITLE_FILE_INVALID;
        }

        return $this->_emailTitles[$title];
    }

    /**
     * Get checksum value from import file if exists
     *
     * @return int checksum | bool false
     */
    public function getChecksumValue()
    {
        $adapter = $this->_getEntityAdapter();

        if (!$this->_isUsingInterface($adapter)) {
            return false;
        }

        return $adapter->getChecksumValue() >= 0 ?
            $adapter->getChecksumValue() :
            false;
    }

    private function _isUsingInterface($adapter)
    {
        if (isset($this->_isUsingInterface)) {
            return $this->_isUsingInterface;
        }

        $class = new ReflectionClass($adapter);
        $this->_isUsingInterface = $class->implementsInterface('Tgc_Datamart_Model_Import_Entity_Interface');

        return $this->_isUsingInterface;
    }

    /**
     * Override to remove the checksum row from count if it exists
     *
     * @return int
     */
    public function getProcessedRowsCount()
    {
        $rowCount = $this->_getEntityAdapter()->getProcessedRowsCount();

        return $this->getChecksumValue() >= 0 ?
            $rowCount - 1 :
            $rowCount;
    }

    public function isChecksumRequired()
    {
        $isChecksumRequired = false;
        $adapter = $this->_getEntityAdapter();
        if ($this->_isUsingInterface($adapter)) {
            $isChecksumRequired = true;
        }

        return $isChecksumRequired;
    }

    /**
     * Validate if checksum is correct
     *
     * @return bool valid or not
     */
    public function validateChecksum()
    {
        $checksum = $this->getChecksumValue();
        $rowCount = $this->getProcessedRowsCount();

        //no checksum in import file?
        if (!$checksum && !$this->isChecksumRequired()) { //if user leaves out checksum, it is not value, therefore, it will not return true if this is omitted.
            return true;
        }
        if ($this->_getEntityAdapter()->getErrorsCount() >= $this->_getEntityAdapter()->getErrorsLimit()) {
            return true;
        }

        return $checksum == $rowCount;
    }

    /**
     * Move uploaded file and create source adapter instance.
     *
     * @throws Mage_Core_Exception
     * @return string Source file path
     */
    public function uploadSource()
    {
        $entity    = $this->getEntity();
        $uploader  = Mage::getModel('core/file_uploader', self::FIELD_NAME_SOURCE_FILE);
        $uploader->skipDbProcessing(true);
        $result    = $uploader->save(self::getWorkingDir());
        $extension = pathinfo($result['file'], PATHINFO_EXTENSION);

        $uploadedFile = $result['path'] . $result['file'];
        if (!$extension) {
            unlink($uploadedFile);
            Mage::throwException(Mage::helper('importexport')->__('Uploaded file has no extension'));
        }

        /* Process custom file reformatting */
        if(in_array($entity, $this->_reformatEntityFiles))
        {
            // Hand off file path to our reformatting helper
            $reformattedFile = Mage::helper('tgc_datamart/'.$entity)->processFileReformat($uploadedFile);
            $uploadedFile = $reformattedFile;
        }

        $sourceFile = self::getWorkingDir() . $entity;

        $sourceFile .= '.' . $extension;

        if(strtolower($uploadedFile) != strtolower($sourceFile)) {
            if (file_exists($sourceFile)) {
                unlink($sourceFile);
            }

            if (!@rename($uploadedFile, $sourceFile)) {
                Mage::throwException(Mage::helper('importexport')->__('Source file moving failed'));
            }
        }
        // trying to create source adapter for file and catch possible exception to be convinced in its adequacy
        try {
            $this->_getSourceAdapter($sourceFile);
        } catch (Exception $e) {
            unlink($sourceFile);
            Mage::throwException($e->getMessage());
        }
        return $sourceFile;
    }
}