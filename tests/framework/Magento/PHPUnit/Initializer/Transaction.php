<?php

/**
 * Initializer of Transaction for integration tests
 *
 * @category    Magento
 * @package     Magento_PHPUnit
 */
class Magento_PHPUnit_Initializer_Transaction extends Magento_PHPUnit_Initializer_Abstract
{
    /**
     * Database connection
     *
     * @var Mage_Core_Model_Resource_Abstract
     */
    protected $_connection;

    /**
     * Returns database connection object
     *
     * @return Mage_Core_Model_Resource_Abstract
     */
    public function getConnection()
    {
        if (!$this->_connection) {
            $this->_connection = $this->getDefaultConnection();
        }
        return $this->_connection;
    }

    /**
     * Returns default DB connection
     *
     * @return Mage_Core_Model_Resource_Abstract
     */
    public function getDefaultConnection()
    {
        return Magento_PHPUnit_Config::getInstance()->getDefaultConnection();
    }

    /**
     * Sets database connection
     *
     * @param Mage_Core_Model_Resource_Abstract $connection
     */
    public function setConnection($connection)
    {
        $this->_connection = $connection;
    }

    /**
     * Runs initialization process.
     */
    public function run()
    {
        $this->getConnection()->beginTransaction();
    }

    /**
     * Rollback all changes after the test is ended (on tearDown)
     */
    public function reset()
    {
        $this->getConnection()->rollBack();
    }
}
