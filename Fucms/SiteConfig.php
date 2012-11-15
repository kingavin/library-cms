<?php
namespace Fucms;

class SiteConfig
{
	public $apiKey = 'zvmiopav7BbuifbahoUifbqov541huog5vua4ofaweafeq98fvvxreqh';
	
	public $organizationCode;
	public $remoteSiteId;
	public $globalSiteId;
	
	public $extUrl;
	public $libUrl;
	public $fileFolderUrl;
	
	public function __construct($serverConfig, $siteArr)
	{
		$libVersion = $serverConfig['libVersion'];
		$fileServer = $serverConfig['fileServer'];
		
		$this->organizationCode	= $siteArr['organizationCode'];
		$this->remoteSiteId		= $siteArr['remoteSiteId'];
		$this->globalSiteId		= $siteArr['globalSiteId'];
		
		$this->libUrl = 'http://'.$fileServer."/cms/".$libVersion;
		$this->extUrl = 'http://'.$fileServer."/ext";
		$this->fileFolderUrl = "http://storage.aliyun.com/public-misc/".$this->remoteSiteId;
		$this->mongoServer = '127.0.0.1';
	}
}