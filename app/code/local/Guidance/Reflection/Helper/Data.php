<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     Reflection
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/*
 * utilities to get information about classes through PHP Reflection API
 */
class Guidance_Reflection_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getGrandParentClassName($className){
        $selfReflection = new ReflectionClass($className);
        $parentReflection = new ReflectionClass($selfReflection->getParentClass()->getName());
        $grandParentClassName = $parentReflection->getParentClass()->getName();

        return $grandParentClassName;
    }
}
?>