<?php
/**
 * Product entity for importexport
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Dax
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Model_Import_Entity_Product extends Enterprise_ImportExport_Model_Import_Entity_Product
{
    public function __construct()
    {
        parent::__construct();
        $this->_dataSourceModel->setEntityTypeCode($this->getEntityTypeCode());
    }
}
