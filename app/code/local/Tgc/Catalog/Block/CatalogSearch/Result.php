<?php
class Tgc_Catalog_Block_CatalogSearch_Result extends Mage_CatalogSearch_Block_Result
{
    /**
     * Set search available list orders
     *
     * @return Mage_CatalogSearch_Block_Result
     */
    public function setListOrders()
    {
        /* @var $category Mage_Catalog_Model_Category */
        $availableOrders = $this->getListBlock()->getToolbarBlock()->getAvailableOrders();
        $availableOrders = array_merge(array(
            'relevance' => $this->__('Relevance'),
        ), $availableOrders);

        $this->getListBlock()
            ->setAvailableOrders($availableOrders)
            ->setDefaultDirection('desc')
            ->setSortBy('relevance');

        return $this;
    }
}
