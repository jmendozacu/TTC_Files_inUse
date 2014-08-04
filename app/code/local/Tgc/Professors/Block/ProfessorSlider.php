<?php
/**
 * Cms additions
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Cms
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Block_ProfessorSlider extends Mage_Core_Block_Template
implements Mage_Widget_Block_Interface
{
    const RESIZE_WIDTH  = 166;
    const RESIZE_HEIGHT = 162;

    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();

        if (empty($this->_template)) {
            $this->setTemplate('cms/widget/professorSlider.phtml');
        }
    }

    public function getNumProfessors()
    {
        return intval($this->getData('num_professors'));
    }

    public function getCollection()
    {
        $collection = Mage::getModel('profs/professor')
            ->getCollection()
            ->addFieldToSelect('*')
            ->setOrder('rank', 'asc');

        $collection->getSelect()->joinLeft(
            array('teaching' => 'professor_teaching'),
            '(teaching.professor_id = main_table.professor_id)',
            array()
        )->joinLeft(
            array('am' => 'professor_alma_mater'),
            '(am.professor_id = main_table.professor_id)',
            array()
        )->joinLeft(
            array('am_institution' => 'institution'),
            '(am_institution.institution_id = am.institution_id)',
            array('am_name' => 'name')
        )->joinLeft(
            array('teaching_institution' => 'institution'),
            '(teaching_institution.institution_id = teaching_institution.institution_id)',
            array('teaching_name' => 'name')
        )->group('main_table.professor_id');

        $collection->getSelect()->limit($this->getNumProfessors());

        return $collection;
    }

    public function renderName($prof)
    {
        return trim(
            $prof->getTitle() . ' ' . $prof->getFirstName() . ' ' . $prof->getLastName() . ' ' . $prof->getQual()
        );
    }

    public function getProfImage($prof)
    {
        return (string) $this->helper('profs/image')
            ->init($prof)
            ->setSkinPlaceholder('images/tgc/professor.png')
            ->resize(self::RESIZE_WIDTH, self::RESIZE_HEIGHT);
    }
}
