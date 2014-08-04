<?php
/**
 * Bazaarvoice
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Bazaarvoice
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Bazaarvoice_Model_Profile extends Mage_Dataflow_Model_Profile
{
    public function _parseGuiData()
    {
        if (!$this->_isReview()) {
            return parent::_parseGuiData();
        }

        $nl = "\r\n";
        $p = $this->getGuiData();

        $fileXml = '<action type="tgc_bv/convert_adapter_io" method="load">' . $nl;
        $fileXml .= '    <var name="type">' . $p['file']['type'] . '</var>' . $nl;
        $fileXml .= '    <var name="path">' . $p['file']['path'] . '</var>' . $nl;
        $fileXml .= '    <var name="filename"><![CDATA[' . $p['file']['filename'] . ']]></var>' . $nl;
        if ($p['file']['type']==='ftp') {
            $hostArr = explode(':', $p['file']['host']);
            $fileXml .= '    <var name="host"><![CDATA[' . $hostArr[0] . ']]></var>' . $nl;
            if (isset($hostArr[1])) {
                $fileXml .= '    <var name="port"><![CDATA[' . $hostArr[1] . ']]></var>' . $nl;
            }
            if (!empty($p['file']['passive'])) {
                $fileXml .= '    <var name="passive">true</var>' . $nl;
            }
            if ((!empty($p['file']['file_mode']))
                    && ($p['file']['file_mode'] == FTP_ASCII || $p['file']['file_mode'] == FTP_BINARY)
            ) {
                $fileXml .= '    <var name="file_mode">' . $p['file']['file_mode'] . '</var>' . $nl;
            }
            if (!empty($p['file']['user'])) {
                $fileXml .= '    <var name="user"><![CDATA[' . $p['file']['user'] . ']]></var>' . $nl;
            }
            if (!empty($p['file']['password'])) {
                $fileXml .= '    <var name="password"><![CDATA[' . $p['file']['password'] . ']]></var>' . $nl;
            }
        }
        $fileXml .= '    <var name="format"><![CDATA[' . $p['parse']['type'] . ']]></var>' . $nl;
        $fileXml .= '</action>' . $nl . $nl;

        $parseFileXml = '<action type="tgc_bv/convert_parser_review" method="parse">' . $nl;
        $parseFileXml .= '    <var name="single_sheet"><![CDATA['
            . ($p['parse']['single_sheet'] !== '' ? $p['parse']['single_sheet'] : '')
            . ']]></var>' . $nl;

        $parseFileXmlInter = $parseFileXml;
        $parseFileXmlInter .= '    <var name="store"><![CDATA[' . $this->getStoreId() . ']]></var>' . $nl;

        $numberOfRecords = isset($p['import']['number_of_records']) ? $p['import']['number_of_records'] : 1;
        $decimalSeparator = isset($p['import']['decimal_separator']) ? $p['import']['decimal_separator'] : ' . ';
        $parseFileXmlInter .= '    <var name="number_of_records">'
            . $numberOfRecords . '</var>' . $nl;
        $parseFileXmlInter .= '    <var name="decimal_separator"><![CDATA['
            . $decimalSeparator . ']]></var>' . $nl;
        $xml = $fileXml;
        $xml .= $parseFileXmlInter;
        $xml .= '    <var name="adapter">tgc_bv/convert_adapter_review</var>' . $nl;
        $xml .= '    <var name="method">parse</var>' . $nl;
        $xml .= '</action>';

        unset($p['export'], $p['map'], $p['product'], $p['customer']);

        $this->setGuiData($p);
        $this->setActionsXml($xml);

        return $this;
    }

    private function _isReview()
    {
        return $this->getEntityType() == Tgc_Bazaarvoice_Model_Convert_Adapter_Review::ENTITY;
    }
}
