<?php
/**
 * Default helper
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Helper_Data extends Mage_Core_Helper_Data
{
    const PROFILE_KEY = 'current_convert_profile';

    const QUOTE_DELIMETER = '"';

    protected $defaultEmailInfo;

    private $_profileNames = array('Courses', 'Sets', 'Giftcerts', 'Priorityprices', 'Listprices', 'Defaultprices', 'Adcodes');

    private $_profiles = null;

    /**
     * DB connection.
     *
     * @var Varien_Adapter_Interface
     */
    protected $_connection;

    public function __construct()
    {
        $this->defaultEmailInfo = new Varien_Object(array(
            'to' => array(
                array(
                    'email' => Mage::getStoreConfig('trans_email/ident_general/email'),
                    'name'  => Mage::getStoreConfig('trans_email/ident_general/name'),
                ),
                array(
                    'email' => Mage::getStoreConfig('trans_email/ident_support/email'),
                    'name' => Mage::getStoreConfig('trans_email/ident_support/name'),
                ),
            ),
            'from_email' => Mage::getStoreConfig('trans_email/ident_support/email'),
            'from_name' => Mage::getStoreConfig('trans_email/ident_support/name'),
        ));

        /** @var _connection Magento_Db_Adapter_Pdo_Mysql */
        $this->_connection = Mage::getSingleton('core/resource')->getConnection('read');
    }


    public function getdefaultEmailInfo()
    {
        return $this->defaultEmailInfo;
    }

    /**
     * Returns current profile from registry
     *
     * @return Mage_Dataflow_Model_Profile
     * @throws DomainException If current profile is undefined
     */
    public function getCurrentProfile()
    {
        $profile = Mage::registry(self::PROFILE_KEY);
        if (!$profile) {
            throw new DomainException('Current profile is undefined.');
        }

        return $profile;
    }

    /**
     * Defines current profile
     *
     * @param Mage_Dataflow_Model_Profile $profile
     * @return Tgc_Dax_Helper_Data Self
     */
    public function setCurrentProfile(Mage_Dataflow_Model_Profile $profile)
    {
        Mage::register(self::PROFILE_KEY, $profile);

        return $this;
    }

    public function getProfileByType($type)
    {
        $profile = Mage::getModel('tgc_dax/profile')
            ->load($type, 'entity_type');

        if ($profile->isObjectNew()) {
            throw new DomainException("Unable to load $type profile.");
        }

        return $profile;
    }

    public function prepareNotificationEmail(Mage_ImportExport_Model_Import $operation, $onlyPartialErrors = false)
    {
        if($operation->getErrorsCount()) {
            $errorMessagesAll = $operation->getErrors();
            $errors = array();
            foreach ($errorMessagesAll as $errorMessage => $rows) {
                if($onlyPartialErrors == false || strpos($errorMessage, 'column is invalid]')) {
                    //only media_format errors, or associated errors contain 'column is invalid', this is how I ferret out partial errors.
                    $errors[] = $errorMessage . ' in rows: ' . implode(', ', $rows);
                }
            }
            $errorMessagesString = implode('<br />', $errors);
            return $errorMessagesString;
        }
    }

    public function sendStandardNotificationEmail($subject = '', $content = '')
    {
        $this->sendNotificationEmail(array(
            'content'=>  $content,
            'subject' => $subject,
        ));
    }

    public function sendNotificationEmail($emailRequestOptions)
    {
        $emailInfo = $this->getdefaultEmailInfo();
        $emailInfo->addData($emailRequestOptions);

        $emailValidation = array();
        if(!$emailInfo->getContent()) { $emailValidation[] = 'content'; }
        if(!$emailInfo->getSubject()) { $emailValidation[] = 'subject'; }
        $emailValidationPassed = count($emailValidation) > 0 ? false : true;

        if($emailValidationPassed) {
            $mail = new Zend_Mail('utf-8');

            foreach($emailInfo->getTo() as $to) {
                $mail->addTo($to['email'], '=?utf-8?B?' . base64_encode($to['name']) . '?=');
            }

            $mail->setBodyHTML($emailInfo->getContent());
            $mail->setSubject('=?utf-8?B?' . base64_encode($emailInfo->getSubject()) . '?=');
            $mail->setFrom($emailInfo->getFromName(), $emailInfo->getFromEmail());

            try {
                $mail->send();
            } catch (InvalidArgumentException $e) {
                Mage::log('Error Sending Dax Email: ' . $e->getMessage());
            } catch (Exception $e) {
                Mage::log('Error Sending Dax Email: Magento tried and failed to send an email about a recent attempt to import data from DAX.');
            }
        } else {
            $error_message = "The request to send an email could not be sent because the following information is missing: " . implode(",",$emailValidation);
            Mage::log($error_message);
        }
    }

    public function formatPid($pid = '')
    {
        $decodedPid = "";
        if($pid) {
            $decodedPid = urldecode(urldecode(urlencode($pid)));
        }
        return $decodedPid;
    }

    /**
     * A mapping of the possible flat rate shipping methods available for use with coupon codes
     *
     * @return array
     */
    public function getFlatRateMethodsForRules()
    {
        return array(
            '2day' => 'premiumrate_2nd_Day_Express',
        );
    }

    public function isGroupAllowedCoupons($groupId = null)
    {
        if (is_null($groupId)) {
            return false;
        }

        $select = $this->_connection->select()
            ->from(array('group' => Mage::getSingleton('core/resource')->getTableName('customer_group')), 'group.allow_coupons')
            ->where('group.customer_group_id = :groupId');

        $bind = array(
            ':groupId' => intval($groupId),
        );

        return (bool)$this->_connection->fetchOne($select, $bind);
    }

    /**
     * Take a given coupon code and determine whether it is imported or not
     *
     * @param string $couponCode
     * @return bool
     */
    public function isImportedCoupon($couponCode)
    {
        if (empty($couponCode)) {
            return false;
        }

        $select = $this->_connection->select()
            ->from(array('rule' => 'salesrule'), 'rule.is_imported')
            ->joinLeft(
            array('coupon' => Mage::getSingleton('core/resource')->getTableName('salesrule_coupon')),
            '(coupon.rule_id = rule.rule_id)',
            array()
        )
            ->where('coupon.code = :couponCode');

        $bind = array(
            ':couponCode' => (string)$couponCode,
        );

        return (bool)$this->_connection->fetchOne($select, $bind);
    }

    protected function _getAdminSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }

    /**
     * @param $rowData
     * @return mixed
     */
    public function processChecksum($rowData, Mage_ImportExport_Model_Import_Entity_Abstract $object)
    {
        $checksumArray = $rowData;
        reset($checksumArray); //sets pointer to first element.
        array_shift($checksumArray); //eliminates first element in the array.
        reset($checksumArray); //resets the pointer, to the second element of original array.
        $checksumRawValue = current($checksumArray);
        $checksumValue = Zend_Validate::is($checksumRawValue, 'Digits') ? $checksumRawValue : null;
        $object->setChecksumValue($checksumValue);
        return $checksumValue; //this returns the value in the second column.
    }

    /**
     * @param $rowData
     * @param $rowNum
     * @return bool
     */
    public function isChecksumValid($rowData, $rowNum, $entity)
    {
        if($rowNum == 0) {
            $validChecksum = false;
            $checksumValue = $this->retrieveChecksumvalueFromCsv($rowData);

            if(!is_numeric($checksumValue)) {  //if an integer is not put in this field, the value is automatically set to false, which is invalid.
                $entity->setChecksumValue(null);
            } elseif($checksumValue) {
                $entity->setChecksumValue($checksumValue);
                $validChecksum = true;
            }

            return $validChecksum;
        }

        return true; //if it is not the first row, checksum does not need to be validated, therefore returns true.
    }

    public function stripNonAlphaNumeric($string)
    {
        return preg_replace('/[^0-9]/i', '', trim($string));
    }

    /**
     * @param $data
     * @return array
     */
    public function userCSVDataAsArray($data)
    {
        $count_quotes = substr_count($data, self::QUOTE_DELIMETER);
        $arrayValues = array();
        if($count_quotes > 0 && $count_quotes % 2 == 0) {
            //if statement only executes if number quotes string is divisble by 2, an string with an odd number cannot be parsed!
            preg_match_all('/"([^"]+)"/', $data, $matches);
            $arrayValues = $matches[0];
            $this->userCSVDataFormatArray($arrayValues);
        } elseif($count_quotes == 0) {
            $arrayValues = explode(',', trim($data));
            $this->userCSVDataFormatArray($arrayValues);
        } else {
            //error statement saying number fields incorrect.
        }

        return $arrayValues;
    }

    public function userCSVDataFormatArray(&$arrayValues) {
        foreach($arrayValues as $arrayValueKey => $arrayValue) {
            $arrayValues[trim($arrayValueKey)] = trim(trim($arrayValue), '"');
        }
    }

    public function formatDateDMY2Datetime($dateCreatedOn)
    {
        if(isset($dateCreatedOn) && $dateCreatedOn) {
            if(Zend_Validate::is($dateCreatedOn, 'Date', array('format'=>'MM-dd-Y HH:mm:ss a'))) {
                $dateArray = explode(' ',$dateCreatedOn);
                $datePartYMD = array_shift($dateArray);
                $datePartYMDasArray = explode('/',$datePartYMD);
                $datePartRemainder = date('H:i:s',strtotime(implode(" ",$dateArray)));
                $dmyFormattedCorrectly = date('Y-m-d', strtotime($datePartYMDasArray[2] . "-" . $datePartYMDasArray[0] . "-" . $datePartYMDasArray[1]));
                $dateFull = $dmyFormattedCorrectly . " " . $datePartRemainder;
                $dateCreatedOn = $dateFull;
            } else {
                $dateCreatedOn = date('Y-m-d H:i:s',strtotime($dateCreatedOn));
            }

            if($dateCreatedOn == Tgc_Dax_Model_Import_Entity_CustomerAcknowledgement::UNIX_START_DATE) {
                $dateCreatedOn = Tgc_Dax_Model_Import_Entity_CustomerAcknowledgement::BLANK_DATE;
            }

            if(!Zend_Validate::is($dateCreatedOn, 'Date', array('format'=>'Y-MM-dd HH:mm:ss')))
            {
                $dateCreatedOn = Tgc_Dax_Model_Import_Entity_CustomerAcknowledgement::BLANK_DATE;
            }
        }

        return $dateCreatedOn;
    }

    /**
     * @param $arrayValues
     */
    public function userCSVDataProcessUnformmatedField(&$arrayValues)
    {
        foreach($arrayValues as $key => $value) {
            $arrayValues[trim($key)] = trim($value);
        }
        array_filter($arrayValues);
    }

    /** Prettifies an XML string into a human-readable and indented work of art
     * @param string $xml The XML as a string or SimpleXML $xml as a simpleXML node
     * @param boolean $html_output True if the output should be escaped (for use in HTML)
     * @return xml
     * @author mborb@guidance.com
     */
    public function xmlpp($xml, $html_output=false)
    {
        if (is_string($xml)) {
            $xml_obj = new SimpleXMLElement($xml);
        } else {
            $xml_obj = $xml;
        }

        $level = 4;
        $indent = 0; // current indentation level
        $pretty = array();

        // get an array containing each XML element
        $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));

        // shift off opening XML tag if present
        if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {
            $pretty[] = array_shift($xml);
        }

        foreach ($xml as $el) {
            if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
                // opening tag, increase indent
                $pretty[] = str_repeat(' ', $indent) . $el;
                $indent += $level;
            } else {
                if (preg_match('/^<\/.+>$/', $el)) {
                    $indent -= $level;  // closing tag, decrease indent
                }
                if ($indent < 0) {
                    $indent += $level;
                }
                $pretty[] = str_repeat(' ', $indent) . $el;
            }
        }
        $xml = implode("\n", $pretty);
        return ($html_output) ? htmlentities($xml) : $xml;
    }

    public function getTranscriptOptionIds()
    {
        $eav = Mage::getSingleton('eav/config');
        $mediaFormatAttr = $eav->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'media_format');
        $transcripts = array(
            Tgc_DigitalLibrary_Model_Observer::DIGITAL_TRANSCRIPT,
            Tgc_DigitalLibrary_Model_Observer::TRANSCRIPT_BOOK,
        );

        return array_map(
            function ($o) {
                return $o['value'];
            },
            array_filter(
                $mediaFormatAttr->getSource()->getAllOptions(),
                function ($o) use ($transcripts) {
                    return in_array($o['label'], $transcripts);
                }
            )
        );
    }
}
