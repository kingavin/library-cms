<?php
namespace Fucms;

class SiteConfig
{
	static protected $siteArr;
	
	public $apiKey = 'zvmiopav7BbuifbahoUifbqov541huog5vua4ofaweafeq98fvvxreqh';

	public $env = 'production';
	
	public $organizationCode;
	public $remoteSiteId;
	public $globalSiteId;
	
	public $dbName;
	
	public $extUrl;
	public $libUrl;
	public $fileFolderUrl;

	public $accountServer;
	
	public function __construct($serverConfig)
	{
		$this->env = $serverConfig['env'];
		$this->accountServer = $serverConfig['accountServer'];
		
		$libVersion = $serverConfig['libVersion'];
		$fileServer = $serverConfig['fileServer'];
		
		$this->organizationCode	= self::$siteArr['organizationCode'];
		$this->remoteSiteId = self::$siteArr['_id']->{'$id'};
		
		$this->globalSiteId		= self::$siteArr['globalSiteId'];

		$this->dbName = 'cms_'.$this->globalSiteId;
		
		$this->libUrl = 'http://'.$fileServer."/cms/".$libVersion;
		$this->extUrl = 'http://'.$fileServer."/ext";
		$this->fileFolderUrl = "http://misc.fucms.com/public-misc/".$this->remoteSiteId;
		$this->mongoServer = '127.0.0.1';
	}
	
	static public function setSiteArr($siteArr)
	{
		self::$siteArr = $siteArr;
	}
}