<?php
/**
 * User: mhidalgo
 * Date: 14/05/14
 * Time: 15:11
 */

class Tgc_Checkout_Block_Onepage extends Mage_Checkout_Block_Onepage
{
    /**
     * Get 'one step checkout' step data
     *
     * @return array
     */
    public function getSteps()
    {
        $steps = array();
        $stepCodes = $this->_getStepCodes();

        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
            if ($this->isCustomerLoggedIn() && $step === "login") {
                $steps[$step]['label'] = "Checkout Method";
                $steps[$step]['allow'] = false;
            }
        }

        return $steps;
    }
}