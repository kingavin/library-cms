<?php
namespace Fucms;

class SiteConfig
{
	public $apiKey = 'zvmiopav7BbuifbahoUifbqov541huog5vua4ofaweafeq98fvvxreqh';
	
	public $enviroment = 'production';
	public $libVersion = 'v1';
	public $organizationCode;
	public $remoteSiteId;
	public $globalSiteId;
	
	public $extUrl;
	public $libUrl;
	public $fileFolderUrl;
	
	public function __construct($env, $libVersion, $siteDoc)
	{
		$this->enviroment = $env;
		$this->libVersion = $libVersion;
		$this->organizationCode	= $siteDoc['organizationCode'];
		$this->remoteSiteId		= $siteDoc['remoteSiteId'];
		$this->globalSiteId		= $siteDoc['globalSiteId'];
		
		$this->libUrl = "http://lib.eo.test/cms/".$libVersion;
		$this->extUrl = "http://lib.eo.test/ext";
		$this->fileFolderUrl = "http://storage.aliyun.com/public-misc/".$this->remoteSiteId;
		
		$this->mongoServer = '127.0.0.1';
	}

	/*
	public function getEnv()
	{
		return $this->_enviroment;
	}
	
	public function getOrgCode()
	{
		return $this->_organizationCode;
	}
	
	public function getRemoteSiteId()
	{
		return $this->_remoteSiteId;
	}
	
	public function getGlobalSiteId()
	{
		return $this->_globalSiteId;
	}
	
	public function getImageUrl()
	{
		return 'http://storage.aliyun.com/public-misc';
	}
	
	public function getSiteFolderPath()
	{
		$url = self::getImageUrl();
		$url.= '/'.$this->_remoteSiteId;
		return $url;
	}
	
	public function extUrl()
	{
		if($this->_enviroment == 'production') {
			$url = "http://st.onlinefu.com/ext";
		} else {
			$url = "http://lib.eo.test/ext";
		}
		return $url;
	}
	
	public function libUrl()
	{
		if($this->_enviroment == 'production') {
			$url = "http://st.onlinefu.com/cms/".$this->_libVersion;
		} else {
			$url = "http://lib.eo.test/cms/".$this->_libVersion;
		}
		return $url;
	}
	
	public function getFileServer()
	{
		if($this->_enviroment == 'production') {
			$url = "http://file.enorange.com";
		} else {
			$url = "http://file.eo.test";
		}
		return $url;
	}
	
	public function getMongoServer()
	{
		if($this->_enviroment == 'production') {
			return 'mongodb://craftgavin:whothirstformagic?@127.0.0.1';
		} else {
			return '127.0.0.1';
		}
	}
	*/
}