<?php
namespace Fucms\Layout;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface, Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\Doctype, Zend\View\Helper\HeadTitle, Zend\View\Helper\HeadMeta;
use Fucms\Brick\Register, Fucms\Brick\Service\RegisterConfig;
use Fucms\Session\Admin as SessionAdmin;

class Front implements ServiceLocatorAwareInterface
{
	protected $sm				= null;
	
	protected $routeMatch		= null;
	protected $context			= null;
	protected $generalSiteInfo	= null;
	protected $stageList		= null;
	protected $brickRegister	= null;
	protected $brickViewList	= null;
	
	public function initLayout(MvcEvent $e)
	{
		$rm = $e->getRouteMatch();
		$this->routeMatch = $rm;
		$controller = $e->getTarget();
		$sm = $this->sm;
		
		$infoDoc = $this->getGeneralSiteInfo();
		
		$doctypeHelper = new Doctype();
		$doctypeHelper->setDoctype('HTML5');
		if(!is_null($infoDoc)) {
			$renderer = $sm->get('Zend\View\Renderer\PhpRenderer');
			$renderer->headTitle($infoDoc->pageTitle);
			$renderer->headMeta()->setName('keywords', $infoDoc->metakey);
			$renderer->headMeta()->setName('description', $infoDoc->metadesc);
		}
		
		$layoutDoc = $this->getLayoutDoc();
		
		$factory = $sm->get('Core\Mongo\Factory');
		$brickRegister = new Register($controller, new RegisterConfig($layoutDoc, $factory));
		$this->brickRegister = $brickRegister;
//		$sm->setService('Brick\Register', $brickRegister);
		//$controller->setBrickRegister($brickRegister);
	
		$sessionAdmin	= new SessionAdmin();
		$viewModel		= $controller->layout();
		$jsList			= $brickRegister->getJsList();
		$cssList		= $brickRegister->getCssList();
	
		$viewModel->setVariables(array(
			'factory' => $factory,
			'sessionAdmin' => $sessionAdmin,
			'layoutFront' => $this,
			'jsList' => $jsList,
			'cssList' => $cssList,
		));
		
		$siteConfig = $sm->get('ConfigObject\EnvironmentConfig');
		$viewHelper = $sm->get('ViewHelperManager');
		$headFileCo = $factory->_m('HeadFile');
		$headFileDocs = $headFileCo->fetchDoc();
		
		$fileUrl = $siteConfig->fileFolderUrl;
		if($sessionAdmin->getUserData('localCssMode') == 'active') {
			$fileUrl = 'http://local.host/'.$siteConfig->globalSiteId;
		}
		
		foreach($headFileDocs as $doc) {
			if($doc->folder == 'helper') {
				if($doc->type == 'css') {
					$viewHelper->get('HeadLink')->appendStylesheet($siteConfig->libUrl.'/front/script/helper/'.$doc->filename);
				} else {
					$viewHelper->get('HeadScript')->appendFile($siteConfig->libUrl.'/front/script/helper/'.$doc->filename);
				}
			} else {
				if($doc->type == 'css') {
					$viewHelper->get('HeadLink')->appendStylesheet($fileUrl.'/'.$doc->filename);
				} else {
					$viewHelper->get('HeadScript')->appendFile($fileUrl.'/'.$doc->filename);
				}
			}
		}
	}
	
	public function getGeneralSiteInfo()
	{
		if($this->generalSiteInfo == null) {
			$factory = $this->sm->get('Core\Mongo\Factory');
			$co = $factory->_m('Info');
			$this->generalSiteInfo = $co->fetchOne();
		}
		return $this->generalSiteInfo;
	}
	
	public function getStageList()
	{
		if($this->stageList == null) {
			$layoutDoc = $this->getLayoutDoc();
			$this->stageList = $layoutDoc->stage;
		}
		return $this->stageList;
	}
	
	public function getBrickViewList()
	{
		if($this->brickViewList == null) {
			$this->brickViewList = $this->brickRegister->renderAll();
		}
		return $this->brickViewList;
	}
	
	public function getBrickRegister()
	{
		return $this->brickRegister;
	}
	
	public function setRouteMatch($routeMatch)
	{
		$this->routeMatch = $routeMatch;
	}
	
	public function getRouteMatch()
	{
		return $this->routeMatch;
	}
	
	public function getContext()
	{
		if(is_null($this->context)) {
			$contextFactory = new ContextFactory();
			$contextFactory->setServiceManager($this->sm);
			$this->context = $contextFactory->getContext($this->routeMatch);
		}
		
		return $this->context;
	}
	
	public function setContext(ContextAbstract $context)
	{
		$this->context = $context;
	}
	
	public function getLayoutDoc()
	{
		$context = $this->getContext();
		return $context->getLayoutDoc();
	}
	
	public function getContextId()
	{
		return $this->context->getId();
	}
	
	public function getLayoutId()
	{
		$layoutDoc = $this->getLayoutDoc();
		return $layoutDoc->getId();
	}

	public function getLayoutType()
	{
		$layoutDoc = $this->getLayoutDoc();
		return $layoutDoc->type;
	}

	public function getLayoutAlias()
	{
		$layoutDoc = $this->getLayoutDoc();
		if($layoutDoc->default == 1) {
			return $layoutDoc->type;
		} else {
			return $layoutDoc->alias;
		}
	}
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->sm = $serviceLocator;
	}

	public function getServiceLocator()
	{
		return $this->sm;
	}
}