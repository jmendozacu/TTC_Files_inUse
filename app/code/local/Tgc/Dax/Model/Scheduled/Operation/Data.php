<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Scheduled_Operation_Data extends Enterprise_ImportExport_Model_Scheduled_Operation_Data
{
    const SERVER_TYPE_SFTP = 'sftp';
    const CRON_20_MIN = '2';
    const CRON_30_MIN = '3';
    const CRON_60_MIN = '6';
    const CRON_2_HOUR = '4';

    public function getServerTypesOptionArray()
    {
        $types = parent::getServerTypesOptionArray();
        $types[self::SERVER_TYPE_SFTP] = Mage::helper('tgc_dax')->__('Remote SFTP');

        return $types;
    }

    /**
     * Adds extra frequencies
     *
     * @see Enterprise_ImportExport_Model_Scheduled_Operation_Data::getFrequencyOptionArray()
     */
    public function getFrequencyOptionArray()
    {
        $options = parent::getFrequencyOptionArray();

        $options[self::CRON_20_MIN] = $this->_getHelper()->__('Each 20 minutes');
        $options[self::CRON_30_MIN] = $this->_getHelper()->__('Each 30 minutes');
        $options[self::CRON_60_MIN] = $this->_getHelper()->__('Each 60 minutes');
        $options[self::CRON_2_HOUR] = $this->_getHelper()->__('Each 2 hours');
        $options[Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_WEEKLY]
            = $this->_getHelper()->__('Each Monday');
        $options[Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_MONTHLY]
            = $this->_getHelper()->__('Each 1st day of a month');

        return $options;
    }

    /**
     * Returns default helper
     *
     * @return Tgc_Dax_Helper_Data
     */
    private function _getHelper()
    {
        return Mage::helper('tgc_dax');
    }
}
