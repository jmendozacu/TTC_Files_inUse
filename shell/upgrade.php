<?php

require_once dirname(__FILE__) . '/abstract.php';

class Guidance_Shell_Upgrade extends Mage_Shell_Abstract
{
    public function run() {
        set_time_limit(0);
        ini_set('memory_limit', '2G');

        // should start the upgrade.
        $start = microtime(true);

        if ($this->getArg('clean-cache')) {
            $app = Mage::app('admin');
            $app->cleanCache();
        }

        try {
            Mage_Core_Model_Resource_Setup::applyAllUpdates();
            Mage_Core_Model_Resource_Setup::applyAllDataUpdates();
        } catch (Exception $e) {
            echo "\n";
            $e->getMessage();
            Mage::logException($e);
            echo "\n\n";
        }

        echo "\n";
        echo "Time For Upgrade: " . (microtime(true) - $start);
        echo "\n\n";
    }
}


$shell = new Guidance_Shell_Upgrade();
$shell->run();