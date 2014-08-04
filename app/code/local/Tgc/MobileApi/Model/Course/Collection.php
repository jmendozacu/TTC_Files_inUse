<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_MobileApi_Model_Course_Collection extends Tgc_MobileApi_Model_Course_Abstract
{
    protected function _retrieve()
    {
        $userId = $this->getRequest()->getParam('userId');
        $response = array('contentlength' => null, 'courseList' => array());
        $customer = $this->_loadCustomerById($userId);

        $this->_addCoursesToResponse($this->_createCollection()->addFilterByWebUserId($userId), $response);
        $this->_addCoursesToResponse($this->_createCollection()->addFreeMarketingFilter(), $response);

        if ($customer->getIsFreeLectureProspect()) {
            $this->_addCoursesToResponse($this->_createCollection()->addFreeProspectFilter(), $response);
        }

        return $response;
    }

    private function _addCoursesToResponse(
        Tgc_DigitalLibrary_Model_Resource_Course_Collection $courses, array &$response)
    {
        $courses->addAttributeToSelect('*');
        $courses->addLecturesInfo();

        foreach ($courses as $c) {
            $response['courseList'][] = array(
                'id'           => $this->_getCourseId($c),
                'title'        => $c->getName(),
                'description'  => $c->getDescription(),
                'mediaType'    => $this->_getMediaType($c->getFormat()),
                'guide'        => $c->getGuidebook(),
                'cat'          => $this->_getCategoryName($c),
                'duration'     => $c->getAudioDuration() + $c->getVideoDuration(),
                'purchaseDate' => $this->_getPurchaseDate($c)
            );
        }

        $response['contentlength'] = count($response['courseList']);
    }

    /**
     *
     * @return Tgc_DigitalLibrary_Model_Resource_Course_Collection
     */
    private function _createCollection()
    {
        return Mage::getResourceModel('tgc_dl/course_collection');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return DateTime
     */
    private function _getPurchaseDate(Mage_Catalog_Model_Product $product)
    {
        if ($product->getDatePurchased()) {
            return DateTime::createFromFormat('Y-m-d', $product->getDatePurchased())->format(self::DATE_FORMAT);
        }
    }
}