<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Model_Cron
{
    /**
     *
     */
    public function importRatings()
    {
        if (!$this->_isBvEnabled()) {
            return;
        }

        $profile = Mage::getModel('dataflow/profile')
            ->getCollection()
            ->addFieldToFilter('name', array('eq' => Tgc_Bazaarvoice_Model_Resource_Setup::PROFILE_NAME))
            ->getFirstItem();

        $host     = $this->_getBvFtpHost();
        $user     = $this->_getBvFtpUsername();
        $password = $this->_getBvFtpPassword();

        $actionsXml = <<<XML
<action type="tgc_bv/convert_adapter_io" method="load">
    <var name="type">ftp</var>
    <var name="path">var/import</var>
    <var name="filename"><![CDATA[bv_teachco_ratings.xml]]></var>
    <var name="host"><![CDATA[{$host}]]></var>
    <var name="port"><![CDATA[21]]></var>
    <var name="passive">true</var>
    <var name="file_mode">2</var>
    <var name="user"><![CDATA[{$user}]]></var>
    <var name="password"><![CDATA[{$password}]]></var>
    <var name="format"><![CDATA[excel_xml]]></var>
</action>

<action type="tgc_bv/convert_parser_review" method="parse">
    <var name="single_sheet"><![CDATA[]]></var>
    <var name="store"><![CDATA[0]]></var>
    <var name="number_of_records">1</var>
    <var name="decimal_separator"><![CDATA[.]]></var>
    <var name="adapter">tgc_bv/convert_adapter_review</var>
    <var name="method">parse</var>
</action>
XML;

        //override in case changed in admin
        $profile->setActionsXml($actionsXml);

        try {
            $profile->run();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * @return bool
     */
    private function _isBvEnabled()
    {
        return (bool)Mage::helper('tgc_bv')->isBvEnabled();
    }

    /**
     * @return string
     */
    private function _getBvFtpHost()
    {
        return (string)Mage::helper('bazaarvoice')->getSFTPHost();
    }

    /**
     * @return string
     */
    private function _getBvFtpUsername()
    {
        return (string)Mage::getStoreConfig('bazaarvoice/general/client_name');
    }

    /**
     * @return string
     */
    private function _getBvFtpPassword()
    {
        return (string)Mage::getStoreConfig('bazaarvoice/general/ftp_password');
    }
}
