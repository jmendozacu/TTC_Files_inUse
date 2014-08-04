<?php

require_once dirname(__FILE__) . '/abstract.php';

/**
 * This shell script is to mark all Out Of Stock products as disabled.
 *
 * It skips products that are already disabled.
 */
class Guidance_Shell_Upgrade extends Mage_Shell_Abstract
{

    /**
     * Collection to retrieve Ids of Out Of Stock products
     *
     * @var Mage_CatalogInventory_Model_Mysql4_Stock_Item_Collection
     */
    protected $stockItemCollection;

    /**
     * Instance of Select object from stockItemCollection to fast access
     *
     * @var Varien_Db_Select
     */
    protected $stockItemCollectionSelect;

    /**
     * Collection to retrieve actual products
     *
     * @var Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    protected $productCollection;

    /**
     * Instance of Select object from productCollection to fast access
     *
     * @var Varien_Db_Select
     */
    protected $productCollectionSelect;

    /**
     * Required to store initial 'columns' part of stockItemCollectionSelect to
     * be able to restore them after each iteration
     *
     * @see Zend_Db_Select::COLUMNS
     *
     * @var Array
     */
    protected $stockItemCollectionSelectColumns;

    /**
     * Required to store initial 'from' part of stockItemCollectionSelect to
     * be able to restore them after each iteration
     *
     * @see Zend_Db_Select::FROM
     *
     * @var Array
     */
    protected $stockItemCollectionSelectFrom;

    /**
     * Required to store initial 'where' part of productCollectionSelect to be
     * able to restore them after each iteration
     *
     * @var Array
     */
    protected $productCollectionSelectWhere;


    /**
     * Initialises stockItemCollection and productCollection with common set of
     * parameters and backs them up.
     */
    protected function initCollections() {

        $this->stockItemCollection =
            Mage::getModel('cataloginventory/stock_item')
                ->getCollection()
                ->addFieldToFilter('is_in_stock', 0)
                ->setPageSize(100);

        $this->productCollection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addFieldToFilter(
            'status',
            Mage_Catalog_Model_Product_Status::STATUS_ENABLED
        );

        $this->backupCollections();
    }


    /**
     * Extracts part of Select objects form collection to restore them after
     * each iteration.
     *
     * The whole idea is to process products by chunks to avoid high memory
     * consumption an to not run out of memory.
     *
     * Second part of idea is to not recreate collections and reset all
     * filtering options ech iteration.
     *
     * Unfortunately, in this case it is only possible to do by saving initial
     * query part and then restore them every iteration.
     *
     * There are two reasons for it:
     *
     * 1. Overloaded load() method of Mage_CatalogInventory_Model_Mysql4_Stock_Item_Collection
     *    implemented such way that it can not be called second time after
     *    clear() method was called on the collection.
     *
     * 2. The way how addIdFilter method works. It adds the IN conditions with
     *    AND between them. In our case conditions are not crossing sets with
     *    leads to empty result.
     */
    protected function backupCollections() {

        $this->stockItemCollectionSelect = $this->stockItemCollection
            ->getSelect();

        $this->productCollectionSelect = $this->productCollection
            ->getSelect();

        $this->stockItemCollectionSelectColumns = $this->stockItemCollectionSelect
            ->getPart(Zend_Db_Select::COLUMNS);

        $this->stockItemCollectionSelectFrom = $this->stockItemCollectionSelect
            ->getPart(Zend_Db_Select::FROM);

        $this->productCollectionSelectWhere = $this->productCollectionSelect
            ->getPart(Zend_Db_Select::SQL_WHERE);

    }


    /**
     * Restores initial collections parts after each iteration and calls clear()
     * on each of them.
     */
    public function clearCollections() {

        $this->stockItemCollectionSelect
            ->setPart(
            Zend_Db_Select::COLUMNS,
            $this->stockItemCollectionSelectColumns
        );

        $this->stockItemCollectionSelect
            ->setPart(
            Zend_Db_Select::FROM,
            $this->stockItemCollectionSelectFrom
        );

        $this->productCollectionSelect
            ->setPart(
            Zend_Db_Select::SQL_WHERE,
            $this->productCollectionSelectWhere
        );

        $this->stockItemCollection->clear();
        $this->productCollection->clear();
    }


    /**
     * Processes all enabled Out Of Stock products to make them disabled.
     *
     * Processing happens in chunks. Prints progress.
     */
    public function run() {

        $this->initCollections();

        $currentPage = 1;
        $lastPage = $this->stockItemCollection->getLastPageNumber();

        $percentPerPage = 100 / $lastPage;

        while ( $currentPage <= $lastPage ) {
            $this->stockItemCollection->setCurPage($currentPage);

            $productIds = Array();
            foreach( $this->stockItemCollection as $stockItem ) {
                $productIds[] = $stockItem->getProductId();
            }

            $this->productCollection->addIdFilter($productIds);

            $percentPerProduct = $percentPerPage / $this->productCollection->count();

            $percentDone = ($currentPage-1)*$percentPerPage;

            $start = time(true);

            foreach( $this->productCollection as $product ) {

                $percentDone+=$percentPerProduct;

                $product
                    ->setStatus(
                    Mage_Catalog_Model_Product_Status::STATUS_DISABLED
                )
                    ->save();

                $timeSpent = time() - $start;

                $timeLeft = $timeSpent*100/$percentDone;

                printf(
                    "%0.4f%% Finished ETA: %s\r",
                    $percentDone,
                    gmdate("H:i:s", $timeLeft)
                );
            }

            $this->clearCollections();

            $currentPage++;
        }

        echo "\nDone\n";
    }
}

$shell = new Guidance_Shell_Upgrade();
$shell->run();
