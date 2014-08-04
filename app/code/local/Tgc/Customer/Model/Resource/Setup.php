<?php
/**
* @category    TGC
* @package     Customer
* @copyright   Copyright (c) 2013 Guidance
* @author      Chris Lohman <clohm@guidance.com>
*/

class Tgc_Customer_Model_Resource_Setup extends Mage_Customer_Model_Resource_Setup
{

    public function getDefaultEntities()
    {
        $entities = parent::getDefaultEntities();

        /* Add two more attributes to the Default Entities for Customer */
        $entities['customer']['attributes']['dax_customer_id'] = array(
            'type'               => 'varchar',
            'label'              => 'DAX Customer Id',
            'input'              => 'text',
            'required'           => false,
            'sort_order'         => 120,
            'position'           => 120,
            'adminhtml_only'     => 1,
        );

        $entities['customer']['attributes']['datamart_customer_pref'] = array(
            'type'               => 'varchar',
            'label'              => 'Datamart Customer Preference',
            'input'              => 'text',
            'required'           => false,
            'sort_order'         => 130,
            'position'           => 130,
            'adminhtml_only'     => 1,
        );

        return $entities;
    }
}