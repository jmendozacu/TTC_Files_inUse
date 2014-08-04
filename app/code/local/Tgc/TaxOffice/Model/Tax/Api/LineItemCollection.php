<?php
/**
 * LineItem collection
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Tgc_TaxOffice
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_TaxOffice_Model_Tax_Api_LineItemCollection implements Iterator
{
    /**
     * Collection's LineItems
     *
     * @var array of Tgc_TaxOffice_Model_Tax_Api_LineItem
     */
    private $_lineItems = array();

    /**
     * Flag, should the collection use base amount for amount or not
     *
     * @var bool
     */
    private $_useBaseAmounts = false;

    /**
     * Constructor
     *
     * @param array $args 'config' is optional argument
     * @throws InvalidArgumentException
     */
    public function __construct($args = array())
    {
        if (!isset($args['config'])) {
            throw new InvalidArgumentException("'config' argument is required");
        } elseif (!($args['config'] instanceof Tgc_TaxOffice_Model_Config)) {
            throw new InvalidArgumentException("'config' must be an instance of Tgc_TaxOffice_Model_Config");
        }

        $this->_config = $args['config'];
    }

    /**
     * Adds line item to the collection
     *
     * @param Tgc_TaxOffice_Model_Tax_Api_LineItem $lineItem
     * @return Tgc_TaxOffice_Model_Tax_Api_LineItemCollection
     */
    public function add($lineItem)
    {
        $this->_lineItems[] = $lineItem;
        return $this;
    }

    /**
     * Returns all items in the collection
     *
     * @return array
     */
    public function getItems()
    {
        return $this->_lineItems;
    }

    /**
     * Sets taking amounts from Base currency or not
     *
     * @param bool $useBaseAmounts
     * @return Tgc_TaxOffice_Model_Tax_Api_LineItemCollection
     */
    public function setUseBaseAmounts($useBaseAmounts)
    {
        $this->_useBaseAmounts = $useBaseAmounts;
        return $this;
    }

    /**
     * Gets taking amounts from Base currency or not
     *
     * @return bool
     */
    public function getUseBaseAmounts()
    {
        return $this->_useBaseAmounts;
    }

    /**
     * Converts all line items into Request LineItems
     *
     * @return array
     */
    public function convertLineItemsIntoRequest()
    {
        $requestLineItems = array();
        foreach ($this->getItems() as $item) {
            /* @var $item Tgc_TaxOffice_Model_Tax_Api_LineItem */
            $requestLineItems[] = $item->convertIntoRequest($this->getUseBaseAmounts());
        }
        return $requestLineItems;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->_lineItems);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_lineItems);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_lineItems);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return key($this->_lineItems) !== null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_lineItems);
    }
}