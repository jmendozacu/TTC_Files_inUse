<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Professors_Helper_Data extends Mage_Core_Helper_Abstract
{
    const PROFESSORS_ATTRIBUTE_CODE = 'professor';

    protected $_professorsCache = array();
    protected $_productProfessors = array();

    public function getProfessorUrl($professor)
    {
        if ($professor instanceof Tgc_Professors_Model_Professor) {
            $id = $professor->getUrlKey() ? $professor->getUrlKey() : $professor->getId();
        } else if ($professor) {
            $id = $professor;
        } else {
            return Mage::getUrl();
        }

        return Mage::getUrl("professors/$id");
    }

    /**
     * Preload professors data using product relation into local cache for future use by other methods
     *
     * @param Varien_Data_Collection|array $products product collection or array of product ids
     * @return \Tgc_Professors_Helper_Data
     */
    public function loadProfessorsByProducts($products)
    {
        if ($products instanceof Varien_Data_Collection) {
            $productIds = array();
            foreach ($products as $product) {
                $productIds[] = $product->getEntityId();
            }
            $products = array_unique($productIds);
        } else {
            $productIds = array();
            foreach ($products as $product) {
                if ($product instanceof Mage_Catalog_Model_Product) {
                    $productIds[] = $product->getId();
                } else if (is_array($product) && isset($product['entity_id'])) {
                    $productIds[] = $product['entity_id'];
                } else if ((string) $product) {
                    $productIds[] = (string) $product;
                }
            }
            $products = $productIds;
        }

        $productProfessors = Mage::getModel('profs/professor')->getResource()->getProfessorIdsByProducts($products);
        $professorIds = array();
        foreach ($products as $productId) {
            if (isset($productProfessors[$productId])) {
                $this->_productProfessors[$productId] = $productProfessors[$productId];
                foreach ($productProfessors[$productId] as $professorId) {
                    $professorIds[$professorId] = $professorId;
                }
            } else {
                $this->_productProfessors[$productId] = array();
            }
        }

        $professorsCollection = Mage::getModel('profs/professor')->getCollection()
            ->addFieldToFilter('professor_id', array('in' => $professorIds));
        foreach ($professorsCollection as $professor) {
            $this->_professorsCache[$professor->getId()] = $professor;
        }

        return $this;
    }

    /**
     * Retrive product professor name
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $ifMultiplePlaceholder
     * @return string
     */
    public function getProfessorNamesForProduct(Mage_Catalog_Model_Product $product, $ifMultiplePlaceholder = 'Taught By Multiple Professors')
    {
        if (!isset($this->_productProfessors[$product->getId()])) {
            $this->loadProfessorsByProducts(array($product->getId()));
        }

        if (!$this->_productProfessors[$product->getId()]) {
            return false;
        }

        if (count($this->_productProfessors[$product->getId()]) > 1 && $ifMultiplePlaceholder !== false) {
            $professorName = $this->__($ifMultiplePlaceholder);
        } else {
            $professorName = array();
            foreach ($this->_productProfessors[$product->getId()] as $professorId) {
                if (isset($this->_professorsCache[$professorId])) {
                    $professor = $this->_professorsCache[$professorId];
                    $professorName[] = $professor->getFirstName() . ' ' . $professor->getLastName();
                }
            }
            $professorName = implode(', ', $professorName);
        }

        return $professorName;
    }
}
