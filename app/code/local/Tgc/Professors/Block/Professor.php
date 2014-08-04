<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Block_Professor extends Mage_Core_Block_Template
{
    /**
     * Returns professor
     *
     * @throws LogicException If professor is undefined
     * @return Tgc_Professors_Model_Professor
     */
    public function getProfessor()
    {
        $professor = parent::getProfessor();
        if (!$professor instanceof Tgc_Professors_Model_Professor) {
            throw new LogicException('Professor is undefined.');
        }

        return $professor;
    }
}