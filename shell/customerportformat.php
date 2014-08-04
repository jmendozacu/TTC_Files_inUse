<?php
/**
 *
 * @author Chris Lohman
 * @category
 * @package
 * @copyright  Copyright (c) 2011 Guidance Solutions (http://www.guidance.com)
 */

require_once 'abstract.php';


class Guidance_Shell_Customerportformat extends Mage_Shell_Abstract
{
    protected $blank_fields_on_multi_address = array(
        'email',
        '_website',
        '_store',
        'confirmation',
        'created_at',
        'created_in',
        'disable_auto_group_change',
        'dob',
        'firstname',
        'gender',
        'group_id',
        'lastname',
        'middlename',
        'password_hash',
        'prefix',
        'reward_update_notification',
        'reward_warning_notification',
        'rp_token',
        'rp_token_created_at',
        'store_id',
        'suffix',
        'taxvat',
        'website_id',
        'password',
        //'customerid'
    );

    protected  function _construct() {

        set_error_handler($this->error_handler());

    }

    public function run()
    {

        $start = time();

        echo  "@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n" .
              "               STARTING Customer reformat            \n" .
              "=====================================================\n" ;

        $source = end($_SERVER['argv']);
        $target = str_replace('.csv', '-processed.csv', $source);
        $fs = fopen($source, 'r');
    
        // open file to be imported for writing
        $fd = fopen($target, 'w');
         
        // retrieve column names
        $fieldColumns = fgetcsv($fs);

        $first = true;
        $saved_customer_email = '';
        $ids_processed = 0;

        // iterate through file
        while ($r = fgetcsv($fs))
        {    
            // get a row as associated array
            $row = array_combine($fieldColumns, $r);

            // trim data
            foreach($row as $key => $val)
            {
                $row[$key] = trim($val);
                if($key == '_address_street' && stripos($val, '|') !== false)
                {
                    $address_lines = explode('|', $val);
                    $row[$key] = $address_lines[0] . "\n" . $address_lines[1];
                }
            }

            // output header
            if ($first) {
                fputcsv($fd, $fieldColumns);
                $first = false;
            }
            
            // if email matches previous row, add address column only, otherwise, write full row
            if($saved_customer_email == $row['email'])
            {
                $new_row = $row;
                // Write only address columns to file, so blank out the customer level items.
                foreach($this->blank_fields_on_multi_address as $blank_field)
                {
                    if(array_key_exists($blank_field, $new_row))
                    {
                        $new_row[$blank_field] = '';
                    }
                }
            }
            else
            {
                $new_row = $row;
            }

            fputcsv($fd, $new_row);
    
            $saved_customer_email = $row['email'];
            $ids_processed++;
        }
        // close files
        fclose($fd);
        fclose($fs);

        $end = time() - $start;
        if($end == 0) $end = 1; // For small runs, let's round up to 1 second.
        $ops = $end > 0 ? $ids_processed/$end : 'NAN';

        echo  "               FIN CUSTOMER REFORMAT                   \n" .
              "=====================================================\n" .
              " Records processed  : $ids_processed                \n" .
              " Time to process   : $end sec                            \n" .
              " Records per Second : $ops o/s                        \n" .
              "@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@\n" ;
    }
    
    static function error_handler(){

        return function($level ,$message)
        {

            if($level === E_USER_WARNING){

                echo "** WARNING ** : " . $message . " ***\n";
                return true;
            }

            return false;
        };
    }
}

$shell = New Guidance_Shell_Customerportformat();
$shell->run();    