<?php
namespace Fucms\Brick;

use Brick\Helper\Twig\Filter as TwigFilter;

class Register
{
	protected $_solidBrickList = array();
	protected $_brickNameList = array();
	protected $_extensionParams = array();
	protected $_jsList = array();
	protected $_cssList = array();
	protected $_cache = null;

	protected $controller = null;

	public function __construct($controller, $config = null)
	{
		$sm = $controller->getServiceLocator();
		TwigFilter::setServiceManager($sm);
		 
		$this->controller = $controller;
		 
		if($config) {
			$config->configRegister($this);
		}
		 
		$frontendOptions = array(
				'lifetime' => 7200
		);
		$backendOptions = array(
				//'cache_dir' => CACHE_PATH
		);
		//$this->_cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
	}

	public function registerBrick($brickDoc)
	{
		if(is_object($brickDoc)) {
			$solidBrick = $brickDoc->createSolid($this->controller);
			$this->_solidBrickList[] = $solidBrick;
		} else if(is_string($brickDoc)) {
			$solidBrick = $this->createSolidBrickFromString($brickDoc);
			$this->_solidBrickList[] = $solidBrick;
		} else if(is_array($brickDoc)) {
			foreach($brickDoc as $brickName) {
				$solidBrick = $this->createSolidBrickFromString($brickName);
				$this->_solidBrickList[] = $solidBrick;
			}
		}

		$effectFiles = $solidBrick->getEffectFiles();
		if(!is_null($effectFiles)) {
			foreach($effectFiles as $filename) {
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if($ext == 'js') {
					$this->_jsList[] = $filename;
				} else {
					$this->_cssList[] = $filename;
				}
			}
		}
		return true;
	}

	public function createSolidBrickFromString($solidClassName)
	{
		$className = '\Brick\\'.$solidClassName;
		$fixedBrick = new $className($this->controller);
		return $fixedBrick;
	}

	public function getBrickList($spriteName = null)
	{
		if(is_null($spriteName)) {
			return $this->_solidBrickList;
		} else {
			$solidBrickList = $this->_solidBrickList;
			$returnBricks = array();
			foreach($solidBrickList as $solidBrick) {
				if($solidBrick->getSpriteName() == $spriteName) {
					$returnBricks[] = $solidBrick;
				}
			}
			return $returnBricks;
		}
	}

	public function getJsList()
	{
		return $this->_jsList;
	}

	public function getJsPath()
	{
		$jsList = array_unique($this->_jsList);
		$jsPath = implode(',', $jsList);
		return $jsPath;
	}

	public function getCssList()
	{
		return $this->_cssList;
	}

	public function getCssPath()
	{
		$cssList = array_unique($this->_cssList);
		$cssPath = implode(',', $cssList);
		return $cssPath;
	}

	public function renderAll()
	{
		$solidBrickList = $this->_solidBrickList;

		$HTML_ARR = array();
		foreach($solidBrickList as $solidBrick) {
			if(array_key_exists($solidBrick->getSpriteName(), $HTML_ARR)) {
				$BrickHTML = $HTML_ARR[$solidBrick->getSpriteName()];
			} else {
				$BrickHTML = "";
			}
			$BrickHTML.= $solidBrick->render();
			$HTML_ARR[$solidBrick->getSpriteName()] = $BrickHTML;
		}
		return $HTML_ARR;
	}
}