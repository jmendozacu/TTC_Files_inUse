<?php
/**
 * Override to fix core bug
 *
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     SalesRule
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_SalesRule_Model_Rule_Condition_Product_Found
    extends Mage_SalesRule_Model_Rule_Condition_Product_Found
{
    /**
     * validate
     *
     * @param Varien_Object $object Quote
     * @return boolean
     */
    public function validate(Varien_Object $object)
    {
        $all = $this->getAggregator()==='all';
        $true = (bool)$this->getValue();
        $found = false;
        if ($object instanceof Mage_Sales_Model_Quote_Item) {
            $found = $all;
            foreach ($this->getConditions() as $cond) {
                $validated = $cond->validate($object);
                if (($all && !$validated) || (!$all && $validated)) {
                    $found = $validated;
                    break;
                }
            }
        } else {
            foreach ($object->getAllItems() as $item) {
                $found = $all;
                foreach ($this->getConditions() as $cond) {
                    $validated = $cond->validate($item);
                    if (($all && !$validated) || (!$all && $validated)) {
                        $found = $validated;
                        break;
                    }
                }
                if (($found && $true) || (!$true && $found)) {
                    break;
                }
            }
        }
        // found an item and we're looking for existing one
        if ($found && $true) {
            return true;
        }
        // not found and we're making sure it doesn't exist
        elseif (!$found && !$true) {
            return true;
        }
        return false;
    }
}
