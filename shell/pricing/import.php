<?php
require_once '../abstract.php';

/**
 * Great Courses Pricing Import Script
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Shell_Pricing_Import extends Mage_Shell_Abstract
{
    // Job names configured in database
    const JOB_CATALOG_CODES    = "DX2M Catalog Codes";
    const JOB_AD_CODES         = "DX2M Ad Codes";
    const JOB_DEFAULT_PRICING  = "DX2M Default Pricing";
    const JOB_LIST_PRICING     = "DX2M List Pricing";
    const JOB_PRIORITY_PRICING = "DX2M Priority Pricing";

    const LOG_FILE = 'price_imports.log';

    protected $_debug;


    /**
     * @return $this|Mage_Shell_Abstract
     */
    public function _construct()
    {
        chdir(Mage::getBaseDir());

        if ($this->getArg('debug')) {
            $this->_debug = true;
        }

        return $this;
    }

    /**
     * Run script
     *
     */
    public function run()
    {
        // Usage help
        if ($this->getArg('importall')) {
            $this->importAll();
        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * @return $this
     */
    public function importAll()
    {
        $this->importDefaultPricing();
        $this->importListPricing();
        $this->importPriorityPricing();
        $this->importCatalogCodes();
        $this->importAdCodes();
    }

    /**
     * @param $jobName
     * @return $this
     */
    public function runOperation($jobName)
    {
        $this->_log('Importing ' . $jobName);
        $start = microtime(true);
        $this->_getOperation($jobName)->run();
        $finish = microtime(true);
        $time = round($finish - $start);
        $this->_log('Job ' . $jobName . ' finished in ' . $time . ' seconds');
        return $this;
    }

    /**
     * @return $this
     */
    public function importCatalogCodes()
    {
        $this->runOperation(self::JOB_CATALOG_CODES);
        return $this;
    }

    /**
     * @return $this
     */
    public function importAdCodes()
    {
        $this->runOperation(self::JOB_AD_CODES);
        return $this;
    }

    /**
     * @return $this
     */
    public function importDefaultPricing()
    {
        $this->runOperation(self::JOB_DEFAULT_PRICING);
        return $this;
    }

    /**
     * @return $this
     */
    public function importListPricing()
    {
        $this->runOperation(self::JOB_LIST_PRICING);
        return $this;
    }

    /**
     * @return $this
     */
    public function importPriorityPricing()
    {
        $this->runOperation(self::JOB_PRIORITY_PRICING);
        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    protected function _log($message)
    {
        if ($this->_debug) {
            echo $message . PHP_EOL;
        } else {
            Mage::log($message, null, self::LOG_FILE);
        }
        return $this;
    }

    /**
     * @param int $id
     * @return Tgc_Dax_Model_Scheduled_Operation
     */
    protected function _getOperation($id)
    {
        $operation = Mage::getModel('enterprise_importexport/scheduled_operation');
        $operation->load($id, 'name');
        return $operation;
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f import.php -- [options]

  importall         Import all pricing related profiles
  --debug           Log to STDOUT
  help              This help

USAGE;
    }
}

$shell = new Mage_Shell_Pricing_Import();
$shell->run();
