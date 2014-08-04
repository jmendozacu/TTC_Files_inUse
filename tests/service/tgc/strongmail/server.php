<?php
/**
 * Description
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    ${MAGENTO_MODULE_NAMESPACE}
 * @package     ${MAGENTO_MODULE_NAMESPACE}_${MAGENTO_MODULE}
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
//require_once (dirname(__FILE__) . '/../../../../app/Mage.php');

if (empty($_SERVER) || empty($_SERVER['HTTP_HOST'])) {
    exit(1);
}

class SOAP_Service_Secure
{
     protected $class_name    = '';
     protected $authenticated = false;

     // -----

     public function __construct($class_name)
     {
         $this->class_name = $class_name;
     }

     public function Security($Header)
     {
         if($Header->UsernameToken->Username == 'foo' && $Header->UsernameToken->Password == 'bar'
             && $Header->OrganizationToken->organizationName == 'tgc'
         ) {
             $this->authenticated = true;
         }
     }

     public function __call($method_name, $arguments)
     {
         //file_put_contents(dirname(__FILE__) . '\\f.log', print_r($method_name, true) . "\n" . print_r($arguments, true), FILE_APPEND);

         if ($method_name == 'list') {
             $method_name = 'listObjects';
         }

         if(!method_exists($this->class_name, $method_name)){
             throw new Exception('method not found');
         }

         $this->checkAuth();

         return call_user_func_array(array($this->class_name, $method_name), $arguments);

     }

     protected function checkAuth()
     {
         if(!$this->authenticated) {
             throw new Exception('User is not authenticated');
         }
     }
 }

class StrongMail
{
    protected static $_mailings = array(
        'new_order_registered' => 123,
        'reset_password' => 124,
        'new_customer' => 125,
        'new_customer_confirmed' => 126,
        'confirmation_email' => 127
    );

    public function listObjects($arg)
    {
        $array = new ArrayObject();
        if ($arg->filter->nameCondition[0]->operator == 'EQUAL') {
            if (isset(self::$_mailings[$arg->filter->nameCondition[0]->value])) {
                $result = new stdClass();
                $result->id = self::$_mailings[$arg->filter->nameCondition[0]->value];
                $array->append(new SoapVar($result, SOAP_ENC_OBJECT, 'ObjectId', null, 'objectId'));
            }
        }
        return $array;
    }

    public function getTxnMailingHandle($arg)
    {
        //file_put_contents(dirname(__FILE__) . '/x.log', print_r($arg, true));
        if (in_array($arg->mailingId->id, self::$_mailings)) {
            $result = new stdClass();
            $result->handle = 'handle';
            return $result;
        }

        return false;
    }

    public function txnSend($arg)
    {
        if ($arg->handle == 'handle') {
            $record = $arg->sendRecord->Struct;
            if ($record->field->Struct) {
                foreach ($record->field->Struct as $field) {
                    if ($field->name == 'ID' && !empty($field->value)) {
                        $idFound = true;
                    }
                    if ($field->name == 'EMAIL_ADDRESS' && !empty($field->value)) {
                        $emailFound = true;
                    }
                }

                if (empty($idFound) || empty($emailFound)) {
                    return false;
                }

                file_put_contents(
                    dirname(__FILE__) . '/../../../../var/log/txn_send.log',
                    date('Y-m-d H:i:s') . " Email has been sent!\n"."Params: ".print_r($arg, true),
                    FILE_APPEND
                );

                $result = new stdClass();
                $result->success = 1;
                return $result;
            }
        }

        return false;
    }
}


$host = $_SERVER['HTTP_HOST'];

if (!empty($_REQUEST['wsdl'])) {
    $wsdl = file_get_contents(dirname(__FILE__) . '/StrongMail-WSDL_edit.xml');
    $wsdl = str_replace('{{{host}}}', $host, $wsdl);
    echo $wsdl;
} else {
    $wsdl = 'http://'. $host . '/tests/service/tgc/strongmail/StrongMail-WSDL_edit.xml';

    $Service = new SOAP_Service_Secure('StrongMail');

    $Server = new SoapServer($wsdl);

    $Server->setObject($Service);

    $Server->handle();
}