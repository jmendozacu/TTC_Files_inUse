<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Lectures
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

require_once "Mage" . DS . "Adminhtml" . DS . "controllers" . DS . "Catalog" . DS . "ProductController.php";

class Tgc_Lectures_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{

    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Mage_Catalog');
    }

    public function lecturesAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $productId = $this->getRequest()->getParam('id');
        $lectureEditedId = $this->getRequest()->getParam('lectureid');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $data = $this->getRequest()->getPost();
        $messageLectureSave = null;
        $isEdit = (int)($this->getRequest()->getParam('id') != null);
        $lectureIsBeingEdited = false;
        $isDataValid = true;
        $this->getRequest()->setParam('lectureselected', 0); //this param should only be true if user click on item in the grid, the user has just clicked save button, so this param set 0.

        $hasLecturesFormBeenSubmitted = $this->_validationHelper()->hasLecturesFormBeenSubmitted($data);

        if ($hasLecturesFormBeenSubmitted) { //this does not run if lecture form has not been submitted.
                $lectureIsBeingEdited = $lectureEditedId ? true : false;
                $lecturesData = $data;

                $this->_validationHelper()->formatDataToAvoidUndefinedIndexErrors($lecturesData);

                $this->_validationHelper()->convertProfessorToSavableFormat($lecturesData); //reason for putting this earlier, is that if this conversion not performed, and there is validation error, user will have to retype in data.

                $primaryKeyInfo = $this->_validationHelper()->retrieveIdFieldNameAndValue($data, $isDataValid, $lectureIsBeingEdited, $lectureEditedId);

                $this->_validationHelper()->validateKeyFieldsForUniqueness($primaryKeyInfo, $data, $isDataValid); //if neither of the three fields exist that make up the primary key, then adds an error message.

                $lecture = $this->_validationHelper()->loadLecture($primaryKeyInfo['value'],$primaryKeyInfo['name'], $lectureIsBeingEdited, $messageLectureSave, $isDataValid);

                if($isDataValid) {
                    $this->_validationHelper()->isRequestedLectureInvalid($isDataValid);
                    $this->_validationHelper()->validateRequiredFields($lecturesData, $isDataValid);
                }

                if($isDataValid) {
                    $this->_validationHelper()->fieldMustBeNumbersValidation($lecturesData, $isDataValid);
                    $this->_validationHelper()->validateOriginalLectureNumberAndDefaultCourseNumber($lecturesData, $isDataValid);
                    $this->_validationHelper()->doesDefaultCourseNumberMatchExistingProduct($lecturesData['default_course_number'], $isDataValid);
                    $this->_validationHelper()->stripHtmlTags($lecturesData);
                }

                if($isDataValid) {
                    //if lecture number matches an existing lecture number, then increment all lecture numbers that are greater to or equal than.
                    $this->_validationHelper()->lectureNumberExistsForProduct($data['lecture_number'],$productId);
                    $lecturesData['product_id'] = $productId;
                    $lecture->addData($lecturesData);
                    $this->_validationHelper()->unsetBlankValues($lecture); //array unique prevents blank entries from being saved to database as zero, this makes it so these entries are saved as null, NOT zero.

                    try {
                        $lecture->save();
                        $this->_getSession()->addSuccess(
                            Mage::helper('lectures')->__($messageLectureSave)
                        );
                        $this->_getSession()->setLectureFormData(''); //reason for erasing session variable on save, is that if customer goes to a different product, they would see old form data!
                    } catch (Mage_Core_Exception $e) {
                        $this->_getSession()->addError($e->getMessage())
                            ->setProductData($data);
                        $isDataValid = false;
                    } catch (Exception $e) {
                        Mage::logException($e);
                        $this->_getSession()->addError($e->getMessage());
                        $isDataValid = false;
                    }
                } else {
                    $this->_getSession()->setLectureFormData($lecturesData);
                    $this->getRequest()->setParam('back', true);
                }
        }

        parent::saveAction();

        /**********************************************************************************************/
        //Creating custom handling of redirects.  Needed to handle different situations involving lectures. Also maintains magento redirect functionality.
        if($this->_getSession()->getMessages()->count(Mage_Core_Model_Message::ERROR)) {
            $redirectBack = true;
        }

        $redirectArray = array(
            'id'    => $productId,
            '_current'=>true
        );

        if($lectureIsBeingEdited && $isDataValid && $redirectBack) { //evaluates to true when lecture is edited, and needs return back.
            $redirectArray['lectureid'] = 0; //if lecture has been edited, and has been successfully saved, then we not want lecture data to appear.
            $redirectArray['back'] = 'edit';
            $redirectArray['tab'] = 'product_info_tabs_lectures';
            $this->_redirect('*/*/edit', $redirectArray);
        } elseif($redirectBack) {
            $this->_redirect('*/*/edit', $redirectArray);
        } elseif($this->getRequest()->getParam('popup')) {
            $redirectArray['edit'] = $isEdit;
            $this->_redirect('*/*/created', $redirectArray);
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
        //end of handling redirects section.
        /**********************************************************************************************/
    }

    public function lecturesGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function deleteLectureAction()
    {
        $request = Mage::app()->getFrontController()->getAction()->getRequest();
        $productId = $request->getParam('id');
        $lectureId = $request->getParam('lectureid');

        if($lectureId) {
            Mage::getModel('lectures/lectures')->load($lectureId)->delete();
            $this->_getSession()->setLectureFormData(''); //ensures session data of deleted product does not reappear in the form.
        }

        $this->_redirect('*/*/edit', array('id' => $productId,'back' => 'edit','tab' => 'product_info_tabs_lectures'));
    }

    private function _validationHelper()
    {
        return Mage::helper('lectures/validation');
    }

}