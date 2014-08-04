<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Scheduled_Operation extends Enterprise_ImportExport_Model_Scheduled_Operation
{
    /**
     * Run scheduled operation. If some error ocurred email notification will be send
     *
     * @return bool
     */
    public function run()
    {
        $fileHasNoData = false;
        $operation = $this->getInstance();
        $this->setLastRunDate(Mage::getSingleton('core/date')->gmtTimestamp());
        $result = false;
        try {
            $result = $operation->runSchedule($this);
        } catch(Tgc_Dax_Exception_Import_NoData $e) {
            $fileHasNoData = true;
            $operation->addLogComment($e->getMessage());
        } catch (Exception $e) {
            $operation->addLogComment($e->getMessage());
        }

        if($fileHasNoData && $operation->getErrorsCount() === 0) {
            Mage::getSingleton('adminhtml/session')->addError('The operation was not run, because there are no valid records on the import spreadsheet.');
            $isSuccess = true;
        } elseif(!$result) {
            $isSuccess = false;
        } else {
            $isSuccess = true;
        }

        $filePath = $this->getHistoryFilePath();
        if (!file_exists($filePath)) {
            $filePath = Mage::helper('enterprise_importexport')->__('File has been not created');
        }

        $sendImportErrorsEmail = false;
        if($result && $operation->getErrorsCount()) {
            $sendImportErrorsEmail = true;
        }

        if (!$result || isset($e) && is_object($e) || $sendImportErrorsEmail) {
            $trace = nl2br($operation->getFormatedLogTrace());
            if($sendImportErrorsEmail) {
                $disclaimer = "<br /><b>Invalid Column Errors</b> (Note:The rows below were imported into the database. However, not all columns were saved correctly.)<br />";
                $trace .= $disclaimer . $this->_helperDax()->prepareNotificationEmail($operation, true);
            }
            $operation->addLogComment(
                Mage::helper('enterprise_importexport')->__('Operation finished with fail status')
            );
            $this->sendEmailNotification(array(
                'emailTitle'        => $operation->getEmailTitle($result),
                'operationName'     => $this->getName(),
                'trace'             => $trace,
                'entity'            => $this->getEntityType(),
                'dateAndTime'       => Mage::getModel('core/date')->date(),
                'fileName'          => $filePath
            ));
        }

        $this->setIsSuccess($isSuccess);
        $this->save();

        return $result;
    }

    public function getHistoryFilePath()
    {
        $historyFilepath = parent::getHistoryFilePath();
        $this->setHistoryFilepathName($historyFilepath);
        return $historyFilepath;
    }

    /**
     * Get and initialize file system driver by operation file section configuration
     *
     * @throws Mage_Core_Exception
     * @return Varien_Io_Abstract
     */
    public function getServerIoDriver()
    {
        $fileInfo = $this->getFileInfo();
        $availableTypes = Mage::getModel('enterprise_importexport/scheduled_operation_data')
            ->getServerTypesOptionArray();

        if (empty($fileInfo['server_type']) || !isset($availableTypes[$fileInfo['server_type']])) {
            Mage::throwException(Mage::helper('enterprise_importexport')->__('Invalid server type'));
        }

        $driver = $this->_createDriver($fileInfo['server_type']);
        $driver->setAllowCreateFolders(true);
        $driver->open($this->_prepareIoConfiguration($fileInfo));

        return $driver;
    }

    /**
     * Returns driver by server type
     *
     * @param string $serverType
     * @return Varien_Io_Abstract
     */
    protected function _createDriver($serverType)
    {
        $name = ucfirst(strtolower($serverType));
        $varienClass = "Varien_Io_$name";
        $tgcClass = "Tgc_Dax_Model_Io_$name";

        if (class_exists($tgcClass)) {
            $driverClass = $tgcClass;
        } else if (class_exists($varienClass)) {
            $driverClass = $varienClass;
        } else {
            Mage::throwException(
                Mage::helper('enterprise_importexport')->__(
                    'Neither "%s" nor "%s" server communication class exists.', $class, $tgcClass
                )
            );
        }

        return new $driverClass;
    }

    /**
     * Adds cron task to configuration by frequency and time
     *
     * @return Tgc_Dax_Model_Scheduled_Operation Self
     */
    protected function _addCronTask()
    {
        $cronExprString = $this->_getCronExpr();
        $exprPath  = $this->getExprConfigPath();
        $modelPath = $this->getModelConfigPath();

        try {
            Mage::getModel('core/config_data')
                ->load($exprPath, 'path')
                ->setValue($cronExprString)
                ->setPath($exprPath)
                ->save();

            Mage::getModel('core/config_data')
                ->load($modelPath, 'path')
                ->setValue(self::CRON_MODEL)
                ->setPath($modelPath)
                ->save();
        } catch (Exception $e) {
            Mage::throwException(Mage::helper('cron')->__('Unable to save the cron expression.'));
            Mage::logException($e);
        }
        return $this;
    }

    /**
     * Builds cron expession by time and frequency
     *
     * @return string
     * @throws DomainException On incorrect frequency
     */
    protected function _getCronExpr()
    {
        $frequency = $this->getFreq();
        $time = $this->getStartTime();
        if (!is_array($time)) {
            $time = explode(':', $time);
        }

        list ($min, $hour) = $time;
        $min = (int)$min;
        $hour = (int)$hour;

        switch ($frequency) {
            case Tgc_Dax_Model_Scheduled_Operation_Data::CRON_20_MIN:
                return '*/20 * * * *';

            case Tgc_Dax_Model_Scheduled_Operation_Data::CRON_30_MIN:
                return '*/30 * * * *';

            case Tgc_Dax_Model_Scheduled_Operation_Data::CRON_60_MIN:
                return "$min */1 * * *";

            case Tgc_Dax_Model_Scheduled_Operation_Data::CRON_2_HOUR:
                return '0 */2 * * *';

            case Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_DAILY:
                return "$min $hour * * *";

            case Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_MONTHLY:
                return "$min $hour 1 * *";

            case Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_WEEKLY:
                return "$min $hour * * 1";

            default:
                throw new DomainException('Operation has incorrect frequency.');
        }
    }

    private function _helperDax()
    {
        return Mage::helper('tgc_dax');
    }
}