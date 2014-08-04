<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_ProfessorController extends Mage_Core_Controller_Front_Action
{
    public function viewAction()
    {
        if (!$this->getRequest()->getParam('id')) {
            $this->_redirect('about-us/professors');
        } else {
            try {
                $professor = $this->_loadProfessor();
                $this->loadLayout()
                    ->getLayout()
                    ->getBlock('professor')
                    ->setProfessor($professor);

                $this->getLayout()
                    ->getBlock('products')
                    ->setCollection($this->_prepareProducts($professor->getProducts()));
            } catch (InvalidArgumentException $e) {
                $this->_forward('noroute');
            }

            $this->renderLayout();
        }
    }

    /**
     *
     * @throws InvalidArgumentException
     * @return Tgc_Professors_Model_Professor
     */
    private function _loadProfessor()
    {
        $id = $this->getRequest()->getParam('id');
        $professor = Mage::getModel('profs/professor')->load($id);

        if ($professor->isObjectNew()) {
            throw new InvalidArgumentException('Unable to load professor.');
        }

        return $professor;
    }

    private function _prepareProducts(Mage_Catalog_Model_Resource_Product_Collection $products)
    {
        $attributes = Mage::getSingleton('catalog/config')->getProductAttributes();
        $products->addAttributeToSelect($attributes);
        $products->addPriceData();
        $products->addAttributeToFilter('status', 1);
        $products->addAttributeToFilter('visibility', array('in' => array(2, 3, 4)));

        return $products;
    }
}