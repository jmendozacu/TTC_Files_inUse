<?php

/*
 *  @encoding  UTF-8
 *  @date      Mar 26, 2014
 *  @name      importexport.php
 *  @author    Chris Lohman (clohm@guidance.com)
 */

/*
 *  How to use this shell script:
 *  1. Upload your import CSV to var/importexport
 *  2. Put files into their own folder in var/importexport.  Make sure no other csv files are in this folder
 *  Run shell/importexport.sh, like so:
 *  nohup ./importexport.sh /path/to/folder/you/just/created/in/var/importexport customer & where customer is the
 *  entity to import.

 */

/**
 * Shell script add a store credit to a list of customers.
 */
require_once 'abstract.php';

class Guidance_Shell_Importexport extends Mage_Shell_Abstract {

    public function getAvailableImports()
    {
        return Mage::getModel('importexport/source_import_entity')->toOptionArray();
    }

    public function isImportAvailable($entity)
    {
        $found = false;
        foreach ($this->getAvailableImports() as $avail)
        {
            if ($entity == $avail['value'])
            {
                $found = true;
                break;
            }
        }
        return $found;
    }

    /**
     * Run script
     *
     */
    public function run()
    {
        $args = $_SERVER['argv'];
        $summary = array();

        if (!isset($args[1])) {
            die("\n**** You must pass in an a absolute file location as 1st parameter. ***\n");
        }

        if (!isset($args[2])) {
            die("\n**** You must pass in an Import Entity as 2nd parameter. ***\n");
        }

        try {
            $file = $args[1];
            $import_entity = $args[2];
            //$pathfile = Mage::getBaseDir().DS.$file;
            if(!file_exists($file))
            {
                die("\n**** File ($file) passed as parameter does not exist. ****\n");
            }
            if(!$this->isImportAvailable($import_entity))
            {
                die("\n**** Requested import does not exist. Here is a list of available imports. Pass a 'value' as the 2nd parameter. ****\n".print_r(array_values($this->getAvailableImports()),1));
            }
            $import = Mage::getModel('importexport/import');

            echo "\n\nStarting file reformat...";
            echo "\n\nStart Time: ".Mage::getModel('core/date')->date('H:i:s m-d-Y')."\n";
            $summary['reformat_start'] = time();
            $file = Mage::helper('tgc_datamart/'.$import_entity)->processFileReformat($file);
            echo "\n\nEnd Time: ".Mage::getModel('core/date')->date('H:i:s m-d-Y')."\n";
            echo "\n\nFile ($file) reformatted.\n\n";
            $summary['reformat_end'] = time();

            $import->setEntity($import_entity);
            if($file)
            {
                echo "\n\nStarting validation...";
                echo "\n\nStart Time: ".Mage::getModel('core/date')->date('H:i:s m-d-Y')."\n";
                $summary['validation_start'] = time();
                $validationResult = $import->validateSource($file);
                if($import->getProcessedRowsCount() > 0)
                {
                    if(!$validationResult)
                    {
                        $message = sprintf("\nFile %s contains %s corrupt records (from a total of %s)\n",
                            $file, $import->getInvalidRowsCount(), $import->getProcessedRowsCount()
                        );
                        foreach ($import->getErrors() as $type => $lines)
                        {
                            $message .= "\n:::: ". $type . " ::::\nIn Line(s) ". implode(', ', $lines) . "\n";
                        }
                        if($import->getErrorsCount() >= $import->getErrorsLimit())
                        {
                            echo "\n\nTotal Errors (".$import->getErrorsCount()." exceeds Error Limit (".$import->getErrorsLimit().")";
                            echo "\nEnd Time: ".Mage::getModel('core/date')->date('H:i:s m-d-Y')."\n";
                            Mage::throwException($message);
                        }
                        else
                        {
                            echo "\n\nValidation Result: Pass\n\nWarnings Issued:\n";
                            echo $message;
                        }
                    }
                    echo "\n\nEnd Time: ".Mage::getModel('core/date')->date('H:i:s m-d-Y')."\n";
                    $summary['validation_end'] = time();
                    echo "\n\nFile ($file) validated.\n\n";
                    echo "\n\nStarting import...";
                    echo "\n\nStart Time: ".Mage::getModel('core/date')->date('H:i:s m-d-Y')."\n";
                    $summary['import_start'] = time();
                    $import->importSource();
                    $import->invalidateIndex();
                    echo "\n\nEnd Time: ".Mage::getModel('core/date')->date('H:i:s m-d-Y')."\n";
                    $summary['import_end'] = time();
                    echo "\n\nFile ($file) imported successfully\n\nImport Complete!!\n\n";
                }
            }
        }
        catch(Exception $e)
        {
            echo "\n\n".$e->getMessage()."\n\n";
        }

        echo "\n**************************************************";
        echo "\n*";
        echo "\n* Reformat Runtime: ".$this->formatTime($summary['reformat_end'] - $summary['reformat_start']);
        echo "\n*";
        echo "\n* Validation Runtime: ".$this->formatTime($summary['validation_end'] - $summary['validation_start']);
        echo "\n*";
        echo "\n* Import Runtime: ".$this->formatTime($summary['import_end'] - $summary['import_start']);
        echo "\n*";
        echo "\n**************************************************\n\n";
    }

    public function formatTime($seconds)
    {
        if($seconds <= 0)
        {
            return "0 seconds";
        }
        elseif($seconds < 60)
        {
            return $seconds." seconds";
        }
        elseif($seconds < 3600)
        {
            $min = round($seconds/60);
            $sec = $seconds%60;
            return $min." min ".$sec." sec";
        }
        elseif($seconds < 86400)
        {
            $hour = round($seconds/3600);
            $min = round(($seconds%3600)/60);
            return $hour." hour(s) ".$min." min";
        }
    }

}

$shell = new Guidance_Shell_Importexport();
$shell->run();