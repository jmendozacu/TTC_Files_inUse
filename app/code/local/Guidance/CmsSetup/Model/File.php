<?php
/**
 * Base file class
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Guidance
 * @package     CmsSetup
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Guidance_CmsSetup_Model_File
{
    /**
     * Delimiter header and body
     *
     * @var string
     */
    const BODY_DLM = '---';

    /**
     * Content
     *
     * @var string
     */
    private $_content;

    /**
     * Headers
     *
     * @var array<id=>string>
     */
    private $_headers = array();

    /**
     * Creates
     *
     * @param string $fileName Path on file system
     * @throws InvalidArgumentException If file not exists or enable to read
     */
    public function __construct($fileName)
    {
        if (!is_file($fileName)) {
            throw new InvalidArgumentException("File $fileName does not exist.");
        }

        $content = file_get_contents($fileName);
        if (false === $fileName) {
            throw new InvalidArgumentException("Unable ot read $fileName.");
        }

        $parts = explode(self::BODY_DLM, $content, 2);
        if (count($parts) == 1) {
            $this->_content = $parts[0];
        } else {
            $this->_parseHeader($parts[0]);
            $this->_content = $parts[1];
        }
    }

    /**
     * Returns content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Returns header value
     *
     * @param string $name Name
     * @return string|null Value or null is header does not exits
     */
    protected function _getHeader($name)
    {
        $name = $this->_getNormalizedHeaderName($name);

        return isset($this->_headers[$name])
            ? $this->_headers[$name]
            : null;
    }

    /**
     * Parses header
     *
     * @param string $rawHeaders Raw headers
     * @throws DomainException If unable to parse headers
     */
    private function _parseHeader($rawHeaders)
    {
        $result = preg_match_all('/^(.*)\:(.*)$/m', $rawHeaders, $matches);
        if (false === $result) {
            throw new DomainException('Unable to parse headers.');
        }

        for ($i = 0; $i < count($matches[0]); $i++) {
            $this->_headers[$this->_getNormalizedHeaderName($matches[1][$i])] = trim($matches[2][$i]);
        }
    }

    private function _getNormalizedHeaderName($name)
    {
        return trim(strtolower($name));
    }
}