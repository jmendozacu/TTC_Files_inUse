<?php

class Tgc_Adcoderouter_Adminhtml_AdcoderedirectlistController extends Mage_Adminhtml_Controller_Action
{
    protected $hasError;

    const BLANK_DATE = '0000-00-00';
    const BLANK_DATETIME = '0000-00-00 00:00:00';

    public function getHasError()
    {
        return $this->hasError;
    }

    public function setHasError($hasError)
    {
        $this->hasError = $hasError;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_title('Adcode Redirect')
            ->_addBreadcrumb('Catalog','Catalog')
            ->_addBreadcrumb('Ad Code Redirect','Ad Code Redirect');
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_initAction();

        $model = Mage::getModel('adcoderouter/redirects');

        $id = $this->getRequest()->getParam('id');

        if($id) {
            $model->load($id);
            if(!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This record no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getSearchExpression());

        $data = Mage::getSingleton('adminhtml/session')->getRedirectsData(true);
        if (!empty($data)) {
            $model->setData($data);
        }


        Mage::register('adcoderouter_redirects', $model);

        $this->renderLayout();
    }

    public function saveAction()
    {
        if($postData = $this->getRequest()->getPost()) {
            $model = Mage::getSingleton('adcoderouter/redirects');
            $postData['redirect_querystring'] = ''; //redirect querystring is erased whenever record is saved.  This field is not used, so it is not worth updating it when other values changed, therefore, i erase instead of updating it.

            $model->setData($postData);

            try {
                /** @var Querystrings are no longer updated. Instead, when a record is updated, it is deleted */
                $model = $this->_validateSubmittedInformation($model);
                //$model->addData($parameters);//parameters are no longer extracted from url, therefore this line commented out.

                $model->save();

                $this->_getSession()->addSuccess($this->__('The redirect has been saved.'));
                $this->_redirect('*/*/');
                return;
            } catch (InvalidArgumentException $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('An error occurred while saving this redirect.'));
                $this->_getSession()->addError($this->__($e->getMessage()));
            }

            $this->_getSession()->setRedirectsData($postData);
            $this->_redirectReferer();
        }
    }

    private function _validateSubmittedInformation($model)
    {
        $importRedirects = Mage::getModel('tgc_dax/import_entity_adCodeRedirect');

        $parameterTranslations = array(
            'ai'        => 'ad_code',
            'cid'       => 'course_id',
            'profid'    => 'professor_id',
            'catid'     => 'category_id',
            'cmsid'     => 'cms_page_id',
            'storeid'   => 'store_id',
            'pid'       => 'pid',
        );

        foreach($importRedirects->getSpreadSheetFieldsTypeDate() as $dateFieldname) {
            $dateValue = $model->getData($dateFieldname);
            if(!$dateValue && $dateValue != self::BLANK_DATE) {
                throw new InvalidArgumentException(
                    "The ad code redirect cannot be saved because the following field is missing: $dateFieldname."
                );
            }
        }

        foreach ($importRedirects->getParametersIsNumber() as $numberParameter) {
            $valueData = $model->getData($parameterTranslations[$numberParameter]);
            if (!isset($valueData)) {
                continue;
            }
            if ($valueData) {
                // casts each variable that should be an integer, as an integer,
                // this allows us to validate it more easily later.
                $convertToInteger = (int) $valueData;
            } else {
                $convertToInteger = null; //if this line did not exist, 0's would be placed into dabase when values for these fields were really null.
            }

            $model->setData($parameterTranslations[$numberParameter], $convertToInteger);

            if ($valueData === 0) {
                throw new InvalidArgumentException(
                    "This ad code redirect cannot be saved because the parameter $numberParameter was not an integer."
                );
            }
        }

        foreach($importRedirects->getFieldIsNumber() as $numberFieldName) {
            $numberFieldValue = $model->getData($numberFieldName);
            if($numberFieldValue) {
                if(!Zend_Validate::is($numberFieldValue, 'Digits')) {
                    throw new InvalidArgumentException(
                        "This ad code redirect cannot be saved because $numberFieldName was not an integer."
                    );
                }
            }
        }

        foreach ($importRedirects->getParametersRequired() as $requiredParam) {
            $paramValue = $model->getData($parameterTranslations[$requiredParam]);
            if (isset($paramValue) && !$paramValue) {
                throw new InvalidArgumentException(
                    'This ad code redirect cannot be saved '
                        . "because the parameter $requiredParam must exist in the querystring."
                );
            }
        }

        $allParameterValues = $importRedirects->convertFieldnamesToParameterNames($model->getData());
        $importRedirects->validatePageIds($allParameterValues, false);
        $importRedirects->validateAdCodeExists($model->getData('ad_code'), false);

        $isAdTypeValid = $importRedirects->validateAdTypeRequiredFields($model->getAdType(), $model->getMoreDetails());
        if(!$isAdTypeValid) {
            throw new InvalidArgumentException('Welcome Subtitle and More Details are required fields, because the Ad Type "Space Ad" was selected.');
        }

        $storeId = $model->getStoreId();

        $startDateFormatted = $model->getData('start_date') ? date('Y-m-d H:i:s', strtotime($model->getData('start_date'))) : self::BLANK_DATETIME;
        $endDateFormatted   = $model->getData('end_date')   ? date('Y-m-d H:i:s', strtotime($model->getData('end_date'))) : self::BLANK_DATETIME;

        $numberDuplicates = Mage::getModel('adcoderouter/redirects')->getCollection()
            ->addFieldToFilter('search_expression', $model->getSearchExpression())
            ->addFieldToFilter('store_id', $storeId)
            ->addFieldToFilter('start_date', $startDateFormatted)
            ->addFieldToFilter('end_date', $endDateFormatted);

        if($model->getId()) { //if an ad code that exists in db is being edited, this prevents duplicate checker from thinking that the record is a duplicate of itself.
            $numberDuplicates->addFieldToFilter('id', array(
                    'neq' => $model->getId()
                ));
        }

        if ($numberDuplicates->count() > 0) {
            throw new InvalidArgumentException('This ad code redirect cannot be saved because another record exists with the same request path and store id.');
        }

        return $model;
    }

    public function deleteAction()
    {
        if($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('adcoderouter/redirects')->load($id);
                if($model->getId()) {
                    $model->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The redirect has been successfully deleted.'));
                } else {
                    Mage::getSingleton('adminhtml/session')->addError($this->__('The record was not deleted, becuase it did not exist.'));
                }
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
                    catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occurred while deleting a redirect.'));
            }
        }

        $this->_redirect('*/*/');
    }

    private function _getStoreByCode($fakeCode, $importRedirectsObject)
    {
        $mappings = $importRedirectsObject->getStoreCodeMappings();
        $code = $mappings[$fakeCode];
        $store = Mage::getModel('core/store')->load($code);

        if ($store->isObjectNew()) {
            throw new InvalidArgumentException("Store $code does not exist. Please enter a valid store code.");
        }

        return $store->getId();
    }

    private function _helperDax()
    {
        return Mage::helper('tgc_dax');
    }

}