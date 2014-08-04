<?php


/**
 * Welcome container
 */
class Tgc_Adcoderouter_Model_Pagecache_Container_Setsrightsidebar extends Tgc_Adcoderouter_Model_Pagecache_Container_Adcoderedirect
{
    /**
     * Get cache identifier
     *
     * @return string
     */
    protected function _getCacheId()
    {
        return 'CONTAINER_SETSRIGHTSIDEBAR_' . md5($this->_placeholder->getAttribute('cache_id') . $this->_getIdentifier());
    }
}
