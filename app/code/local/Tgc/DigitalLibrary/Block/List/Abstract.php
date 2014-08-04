<?php
/**
 * Digital Library
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DigitalLibrary
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_DigitalLibrary_Block_List_Abstract extends Mage_Catalog_Block_Product_List
{
    private $_customer;

    protected function _getCustomer()
    {
        if (isset($this->_customer)) {
            return $this->_customer;
        }

        $session  = Mage::getSingleton('customer/session');
        $customer = $session->getCustomer();
        $this->_customer = $customer;

        return $this->_customer;
    }

    protected function _getCourseUrl($product, $format = 0, $last = null)
    {
        $params = array('id' => $product->getId());
        $params['format'] = $format;
        if (!is_null($last)) {
            $params['resume'] = empty($last) ? 1 : $last;
        }
        return Mage::getUrl('*/course/view', $params);
    }

    public function getProgressPercent($product)
    {
        $totalDuration = intval($product->getTotalDuration());
        $totalProgress = intval($product->getTotalProgress());

        if (empty($totalDuration) || empty($totalProgress)) {
            return 0;
        }

        return intval($totalProgress / $totalDuration * 100);
    }

    public function getButtonLabel($progress, $format)
    {
        if ($format) {
            if ($progress < 0.01) {
                return $this->__('Watch Now');
            } else if ($progress > 99.9) {
                return $this->__('Watch Again');
            }
        } else {
            if ($progress < 0.01) {
                return $this->__('Listen Now');
            } else if ($progress > 99.9) {
                return $this->__('Listen Again');
            }
        }
        return $this->__('Resume');
    }

    public function addLectureData(&$product)
    {
        $webUserId   = $this->_getCustomer()->getWebUserId();
        $courseId    = $product->getId();
        $resource    = Mage::getResourceModel('tgc_dl/crossPlatformResume');

        $lectureData = $resource->getLectureData($courseId, $webUserId, $product->getMediaFormat());
        $product->setLastLectureId(0);
        foreach ($lectureData as $lecture) {
            if (!empty($lecture['stream_date']) && $lecture['stream_date'] == $product->getStreamDate()) {
                $lecture['recent'] = true;
                $product->setLastLectureId($lecture['lecture_number']);
            } else {
                $lecture['recent'] = false;
            }
        }
        $product->setNumLectures(count($lectureData));
        $product->setLectureData($lectureData);
    }

    public function getProfessorName($course, $ifMultiplePlaceholder = 'Taught By Multiple Professors')
    {
        $data = $course->getProfessor();
        $professorIds = explode(',', $data);

        if (empty($professorIds)) {
            return '';
        }

        $collection = Mage::getResourceModel('profs/professor_collection')
            ->addFieldToFilter('professor_id', array('in' => $professorIds))
            ->addFieldToSelect('first_name')
            ->addFieldToSelect('last_name')
            ->addFieldToSelect('title');

        $numProfessors = count($collection);
        $return = '';
        $iterator = 0;
        foreach ($collection as $professor) {
            if ($iterator == 1 && $ifMultiplePlaceholder) {
                return $ifMultiplePlaceholder;
            }
            $iterator++;
            $return .= $professor->getTitle() . ' ' . $professor->getFirstName() . ' ' . $professor->getLastName();
            if ($iterator < $numProfessors) {
                $return .= ', ';
            }
        }

        return $return;
    }

    public function getGuidebookUrl($product)
    {
        return Mage::helper('tgc_dl/akamai')->getGuidebookUrl($product);
    }

    public function getTranscriptUrl($product)
    {
        return Mage::helper('tgc_dl/akamai')->getTranscriptUrl($product);
    }
}
