<?php
/**
 * Bas files collection filteren by html extension
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     CmsSetup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_CmsSetup_Model_Files extends FilterIterator
{
    /**
     * Constructs files collection
     *
     * @param string $directory
     */
    public function __construct($directory)
    {
        parent::__construct(new DirectoryIterator($directory));
    }

    /**
     * Returns true if current file extension if html
     *
     * @see FilterIterator::accept()
     */
    public function accept()
    {
        $file = $this->getInnerIterator()->current();

        return $file->isFile() && strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION)) == 'html';
    }
}