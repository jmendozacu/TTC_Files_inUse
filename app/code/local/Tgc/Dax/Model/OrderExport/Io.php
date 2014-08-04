<?php
/**
 * Dax integration
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_OrderExport_Io extends Varien_Io_Sftp
{
    public function write($filename, $src, $mode=null)
    {
        return $this->_connection->put($filename, $src, $mode);
    }
}
