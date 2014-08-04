<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Source_System_Config_Backend_CronExpression extends Mage_Core_Model_Config_Data
{
    const INVALID_CRON_ERROR = 'The cron expression you entered is not valid';

    public function save()
    {
        $cronExpr = $this->getValue();

        if ($this->_validate($cronExpr)) {
            return parent::save();
        }

        $error = self::INVALID_CRON_ERROR;
        Mage::throwException($error);
    }

    private function _validate($cron)
    {
        $regexp = $this->_buildRegexp();
        $parts = explode(' ', $cron);
        foreach ($parts as $part) {
            if (!preg_match($regexp, $part)) {
                return false;
            }
        }

        return (count($parts) == 5);
    }

    private function _buildRegexp() {
        return '/^(?:[1-9]?\d|\*)(?:(?:[\/-][1-9]?\d)|(?:,[1-9]?\d)+)?$/';
    }
}
