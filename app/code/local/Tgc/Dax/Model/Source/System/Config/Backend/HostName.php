<?php
/**
 * Backend model for ftp/port value
 *
 * @author Guidance Magento Team <magento@guidance.com>
 * @category Tgc
 * @package Dax
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Source_System_Config_Backend_HostName extends Mage_Core_Model_Config_Data
{
    const HOST_UNREACHABLE_ERROR = 'Unable to reach the hostname entered';

    public function save()
    {
        $hostname = (string)$this->getValue();
        if (empty($hostname)) {
            return parent::save();
        }

        $hostname = preg_replace("(https?://)", '', $hostname, -1, $changed);
        if ($changed) {
            $this->setValue($hostname);
        }

        $this->_testConnection($hostname);

        return parent::save();
    }

    protected function _testConnection($hostname)
    {
        $configPort = Mage::getStoreConfig('dax/sftp/port');
        $ports[] = 22;
        if ($configPort) {
            $ports = array_merge(array($configPort), $ports);
            $ports = array_unique($ports);
        }

        $reachable = false;
        foreach ($ports as $port) {
            $reachable = $this->_isReachable($hostname, $port);
            if ($reachable) {
                break;
            }
        }

        if (!$reachable) {
            Mage::getSingleton('core/session')
                ->addWarning(Mage::helper('tgc_dax')->__(self::HOST_UNREACHABLE_ERROR));
        }
    }

    protected function _isReachable($host, $port = 80, $timeout = 6)
    {
        $result = @fsockopen($host, $port, $errorNo, $errorString, $timeout);

        return !$result;
    }
}
