<?php

/**
 * RocketWeb
 *
 * @category   RocketWeb
 * @package    RocketWeb_Podcast
 * @copyright  Copyright (c) 2012 RocketWeb (http://rocketweb.com)
 * @author     RocketWeb
 */


class RocketWeb_Podcast_Helper_Data extends Mage_Core_Helper_Data 
{	
    const PODCAST_DIRECTORY_PATH     = 'RocketWeb/Podcasts/';
    const PODCAST_DIRECTORY_CHANNEL  = 'RocketWeb/Podcasts/Channel/';
    const PODCAST_DEFAULT_ROUTE      = 'podcasts';
    const URL_ID_SALT                = 'RocketWeb';
    
    private $allow_audio_extensions  = array('mp3','ogg');

    public function getPodcastDirectoryPath()
    {
        $path = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS . self::PODCAST_DIRECTORY_PATH;
        if(!is_dir($path)) {
            @mkdir($path);
            @chmod($path, 0755);
        }
        return $path;
    }
    
    public function getPodcastDirectoryUrl($store_id = null)
    {
        return Mage::app()->getStore($store_id)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA,false). self::PODCAST_DIRECTORY_PATH;
    }
    
    public function getPodcastDirectoryChannelUrl($store_id = null)
    {
        return Mage::app()->getStore($store_id)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA,false). self::PODCAST_DIRECTORY_CHANNEL;
    }
    
    public function getRoute() {

        $route = Mage::getStoreConfig('rocketweb_podcast/settings/route');

        if (!$route) {
            $route = self::PODCAST_DEFAULT_ROUTE;
        }
        return $route;
    }
    
    public function getAllowAudioExtensions(){
        return $this->allow_audio_extensions;
    }
    
    public function recursiveReplace($search, $replace, $subject) {
        if (!is_array($subject))
            return $subject;

        foreach ($subject as $key => $value)
            if (is_string($value))
                $subject[$key] = str_replace($search, $replace, $value);
            elseif (is_array($value))
                $subject[$key] = self::recursiveReplace($search, $replace, $value);

        return $subject;
    }
    
    public function encodeUrl($str,$id) {
        $url = $this->strToUrl($str) .'-'. $this->encodeId($id);
        return $url;
    }
    
    public function decodeUrl($str) {
        $aUrl = explode('-',$str);
        $id = end( $aUrl );
        return $this->decodeId($id);
    }
    
    private function strToUrl($str, $delimiter='_') {
        setlocale(LC_ALL, 'en_US.UTF8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
    }
    
    private function encodeId($id) {
        return $id.substr(md5($id.self::URL_ID_SALT),0,2);
    }
    
    private function decodeId($id) {
        if($id==self::encodeId(substr($id,0,-2))) return substr($id,0,-2);
        else return false;
    }
    
    public function clearString($string){
        $str = strip_tags($string,'<script>');
        $str = preg_replace('@<(script)\b.*?>.*?</\1>@si', '', $str);
        $str = preg_replace("/&#?[a-z0-9]{2,8};/i","",$str);
        $str = preg_replace("/ & /i"," &amp; ",$str);
        return $str;
    }
}
