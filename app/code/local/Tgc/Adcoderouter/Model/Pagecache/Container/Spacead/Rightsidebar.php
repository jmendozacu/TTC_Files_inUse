<?php


/**
 * Welcome container
 */
class Tgc_Adcoderouter_Model_Pagecache_Container_Spacead_Rightsidebar extends Tgc_Adcoderouter_Model_Pagecache_Container_Adcoderedirect
{
    /**
     * Get cache identifier
     *
     * @return string
     */
    protected function _getCacheId()
    {
        return 'CONTAINER_SPACEADRIGHTSIDEBAR_' . md5($this->_placeholder->getAttribute('cache_id') . $this->_getIdentifier());
    }
}
