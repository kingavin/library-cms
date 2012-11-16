<?php
namespace Fucms\Session;

use Exception;
use SimpleXMLElement;
use Zend\Json\Json;
use Zend\Session\Container;
use Core\Session\SsoUser;

class Admin extends SsoUser
{
	private static $_md5salt = 'Hgoc&639Jgo';
	private static $_md5salt2 = 'jiohGY6&*9';
	
	public $orgCode;
	
	protected $_sessionContainerName = 'sso\remote_user';
	
	public function setOrgCode($oc)
	{
		$this->orgCode = $oc;
	}
	
	public function getServiceType()
	{
		return 'cms';
	}
	
	public function getServiceKey()
	{
		return 'zvmiopav7BbuifbahoUifbqov541huog5vua4ofaweafeq98fvvxreqh';
	}
	
	public function login($xml)
	{
		if($xml instanceof SimpleXMLElement) {
			$user = $xml;
		}
		if(is_null($user)) {
			return false;
		}
		$startTimeStamp = time();
		$userDataArr = array();
		foreach ($user->children() as $tag => $val) {
	    	$userDataArr[$tag] = (string)$val;
	    }
	    
	    $this->isLogin(true);
	    $userDataArr['localCssMode'] = false;
	    $this->setUserData($userDataArr);
		return true;
	}
	
	public function logout()
	{
		/*
		setcookie('userId', '', 1, '/');
		setcookie('startTimeStamp', '', 1, '/');
		setcookie('userData', '', 1, '/');
		setcookie('liv', '', 1, '/');
		$this->_isLogin = false;
		*/
	}
	
	public function hasPrivilege()
	{
		if(!$this->isLogin()) {
			return false;
		}
		
		if(
			$this->getUserData('userType') != 'designer' &&
			$this->getUserData('userType') != 'enorange-admin' &&
			($this->getUserData('orgCode') != $this->orgCode)
		) {
			return false;
		}
		return true;
	}
	
	public function getHomeLocation()
	{
		return '/';
	}
	
	public function getUserId()
	{
		if($this->isLogin()) {
			return $_COOKIE['userId'];
		}
		return 'nobody';
	}
	
	public function getOrgCode()
	{
		return $this->getUserData('orgCode');
	}
	
	public function getRoleId()
	{
		return 0;
	}
}