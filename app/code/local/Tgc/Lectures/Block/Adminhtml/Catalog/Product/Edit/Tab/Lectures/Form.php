<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Lectures_Block_Adminhtml_Catalog_Product_Edit_Tab_Lectures_Form extends Mage_Adminhtml_Block_Catalog_Form
{

    /**
     *  prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    /**
     * Prepare form
     *
     * @return null
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        // Initialize product object as form property to use it during elements generation
        $form->setDataObject(Mage::registry('product'));



        $request = Mage::app()->getFrontController()->getAction()->getRequest();
        $lecturePrimaryKeyId = $request->getParam('lectureid');  //lecture id corresponds to the field lectures.id (it is NOT lecture_id)
        $islectureSelected = $request->getParam('lectureselected'); //this means that user clicked on a lecture in the grid, when this happens load from the database.
        $isNewLecture = $request->getParam('newlecture');


        $headerText = "Create a New Lecture";
        $loadLecture = false;
        $loadLectureData = null;

        $session = Mage::getSingleton('adminhtml/session');
        $lectureFormData = $session->getLectureFormData();
        $professorsSelected = null;
        if($lecturePrimaryKeyId && $islectureSelected) {
            $lecture = Mage::getModel('lectures/lectures')->load($lecturePrimaryKeyId);
            if($lecture->getId()) {
                $loadLecture = true;
                $headerText = "Edit Lecture:&nbsp;&nbsp;" . $lecture->getTitle();
                $loadLectureData = $lecture->getData();
                $professorsSelected = $lecture->getProfessor();
                $session->setLectureFormData('');
            }
        } elseif($isNewLecture) {
            $session->setLectureFormData('');
            $loadLecture = false;
        } elseif($lectureFormData) {
                $loadLecture = true;
                $headerText = "Edit Lecture:&nbsp;&nbsp;" . $lectureFormData['title'];
                $professorsSelected = $lectureFormData['professor'];
                $loadLectureData = $lectureFormData;
        }



        $fieldset = $form->addFieldset('group_fields_lectures', array(
            'legend' => Mage::helper('lectures')->__($headerText),
            'class' => 'fieldset-wide'
        ));

        $saveButton = $this->getLayout()->createBlock('lectures/adminhtml_catalog_product_edit_tab_lectures_buttons_save')->toHtml();
        $deleteButton = $this->getLayout()->createBlock('lectures/adminhtml_catalog_product_edit_tab_lectures_buttons_delete')->toHtml();
        $addNewButton = $this->getLayout()->createBlock('lectures/adminhtml_catalog_product_edit_tab_lectures_buttons_new')->toHtml();
        $buttonsHtml = $saveButton . $addNewButton . $deleteButton;
        $fieldset->setHeaderBar($buttonsHtml);

        //This new element is being added, ONLY, so that we can make asterisk red.  If required is set to true, that will trigger extra default magento js validation we don't want.
        $fieldset->addType('textcustom','Tgc_Lectures_Block_Adminhtml_Catalog_Product_Edit_Tab_Lectures_Form_Element_Textcustom');

        $fieldset->addField('audio_brightcove_id', 'text', array(
            'name'      => 'audio_brightcove_id',
            'label'     => Mage::helper('lectures')->__('Audio Brightcove ID'),
            'title'     => Mage::helper('lectures')->__('Audio Brightcove ID'),
        ));

        $fieldset->addField('video_brightcove_id', 'text', array(
            'name'      => 'video_brightcove_id',
            'label'     => Mage::helper('lectures')->__('Video Brightcove ID'),
            'title'     => Mage::helper('lectures')->__('Video Brightcove ID'),
        ));

        $fieldset->addField('akamai_download_id', 'text', array(
            'name'      => 'akamai_download_id',
            'label'     => Mage::helper('lectures')->__('Akamai Download ID'),
            'title'     => Mage::helper('lectures')->__('Akamai Download ID'),
        ));

        $fieldset->addField('lecture_number', 'textcustom', array(
            'name'              => 'lecture_number',
            'label'             => Mage::helper('lectures')->__('Lecture Number'),
            'title'             => Mage::helper('lectures')->__('Lecture Number'),
            'class'             => 'lecture-validation-required validate-digits checkuniquesort validate-greater-than-zero',
            'requiredcustom'    => true,
        ));

        $fieldset->addField('title', 'textcustom', array(
            'name'      => 'title',
            'label'     => Mage::helper('lectures')->__('Title'),
            'title'     => Mage::helper('lectures')->__('Title'),
            'class'     => 'speciallongformfieldwidth lecture-validation-required',
            'requiredcustom'    => true,
        ));

        $fieldset->addField('description', 'textarea', array(
            'name'      => 'description',
            'label'     => Mage::helper('lectures')->__('Description'),
            'title'     => Mage::helper('lectures')->__('Description'),
            'class'     => 'speciallongformfieldwidth',
        ));

        $fieldset->addField('default_course_number', 'text', array(
            'name'      => 'default_course_number',
            'label'     => Mage::helper('lectures')->__('Original Course Number'),
            'title'     => Mage::helper('lectures')->__('Original Course Number'),
            'note'      => 'If this lecture belongs to another course by default, include the Course Number for that course here.',
            'class'     => 'validate-digits validate-greater-than-zero',
        ));

        $fieldset->addField('original_lecture_number', 'text', array(
            'name'      => 'original_lecture_number',
            'label'     => Mage::helper('lectures')->__('Original Lecture Number'),
            'title'     => Mage::helper('lectures')->__('Original Lecture Number'),
            'class'     => 'validate-digits validate-greater-than-zero',
        ));

        $professorSource = Mage::getModel('profs/professor_attribute_source');
        $professorsList = $professorSource->getAllOptions(false, true);
        $yesNoOptions = Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray();

        $fieldset->addField('professor', 'multiselect', array(
            'name'     => 'professor',
            'label'    => Mage::helper('lectures')->__('Professor'),
            'title'    => Mage::helper('lectures')->__('Professor'),
            'values'   => $professorsList,
            'value'    => $professorsSelected,
        ));

        $fieldset->addField('audio_duration', 'text', array(
            'name'      => 'audio_duration',
            'label'     => Mage::helper('lectures')->__('Audio Duration'),
            'title'     => Mage::helper('lectures')->__('Audio Duration'),
            'class'     => 'validate-digits validate-greater-than-zero',
            'note'      => 'value must be in seconds.',
        ));

        $fieldset->addField('video_duration', 'text', array(
            'name'      => 'video_duration',
            'label'     => Mage::helper('lectures')->__('Video Duration'),
            'title'     => Mage::helper('lectures')->__('Video Duration'),
            'class'     => 'validate-digits validate-greater-than-zero',
            'note'      => 'value must be in seconds.',
        ));

        $fieldset->addField('audio_available', 'select', array(
            'label'     => Mage::helper('lectures')->__('Audio Available'),
            'title'     => Mage::helper('lectures')->__('Audio Available'),
            'name'      => 'audio_available',
            'values'   => $yesNoOptions,
        ));

        $fieldset->addField('video_available', 'select', array(
            'label'     => Mage::helper('lectures')->__('Video Available'),
            'title'     => Mage::helper('lectures')->__('Video Available'),
            'name'      => 'video_available',
            'values'   => $yesNoOptions,
        ));

        $fieldset->addField('audio_download_filesize', 'text', array(
            'name'      => 'audio_download_filesize',
            'label'     => Mage::helper('lectures')->__('Audio Download File Size'),
            'title'     => Mage::helper('lectures')->__('Audio Download File Size'),
            'class'     => 'validate-number validate-greater-than-zero',
        ));

        $fieldset->addField('video_download_filesize_pc', 'text', array(
            'name'      => 'video_download_filesize_pc',
            'label'     => Mage::helper('lectures')->__('Video Download File Size PC'),
            'title'     => Mage::helper('lectures')->__('Video Download File Size PC'),
            'class'     => 'validate-number validate-greater-than-zero',
        ));

        $fieldset->addField('video_download_filesize_mac', 'text', array(
            'name'      => 'video_download_filesize_mac',
            'label'     => Mage::helper('lectures')->__('Video Download File Size Mac'),
            'title'     => Mage::helper('lectures')->__('Video Download File Size Mac'),
            'class'     => 'validate-number validate-greater-than-zero',
        ));

        if($loadLecture) {
            $form->setValues($loadLectureData);
        }

        $this->setForm($form);
        return $this;
    }
}
