<?php
/**
 * Backend model for sftp port value
 *
 * @author Guidance Magento SuperTeam <magento@guidance.com>
 * @category Tgc
 * @package Dax
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Source_System_Config_Backend_Port extends Mage_Core_Model_Config_Data
{
    const ATYPICAL_PORT_WARNING = 'The port value entered is atypical for SFTP';

    public function save()
    {
        $port = (int)$this->getValue();
        if (empty($port)) {
            return parent::save();
        }

        if ($port != Varien_Io_Sftp::SSH2_PORT) {
            Mage::getSingleton('core/session')
                ->addWarning(Mage::helper('tgc_dax')->__(self::ATYPICAL_PORT_WARNING));
        }

        return parent::save();
    }
}
