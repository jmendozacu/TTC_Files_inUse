<?php
/**
 * DataMart integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Datamart_Helper_Customer extends Mage_Core_Helper_Data
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

    public function processFileReformat($file)
    {
        $data['start'] = time();

        $source = $file;
        $target = str_replace('.csv', '-processed.csv', $source);
        $fs = fopen($source, 'r');

        // open file to be imported for writing
        $fd = fopen($target, 'w');

        // retrieve column names
        $fieldColumns = fgetcsv($fs);
        $fieldColumns[] = 'is_address_row';

        $first = true;
        $saved_customer_email = '';
        $data['ids_processed'] = 0;

        // iterate through file
        while ($r = fgetcsv($fs))
        {
            // get a row as associated array
            $r[] = false; // this corresponds to the field is_row_address. Since we are adding an extra field not visible on spreadsheet,
                          //as a flag, we need to create a blank value
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
                $new_row['is_address_row'] = true;
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
                $new_row['is_address_row'] = false;
            }

            fputcsv($fd, $new_row);

            $saved_customer_email = $row['email'];
            $data['ids_processed']++;
        }
        // close files
        fclose($fd);
        fclose($fs);

        // Collect stats
        $data['end'] = time() - $data['start'];
        if($data['end'] == 0) $data['end'] = 1; // For small runs, let's round up to 1 second.
        $data['ops'] = $data['end'] > 0 ? $data['ids_processed']/$data['end'] : 'NAN';

        //return new file name.  Don't need data stats for now.
        return $target;
    }

}
