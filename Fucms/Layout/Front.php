<?php
namespace Fucms\Layout;

use Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Front implements ServiceLocatorAwareInterface
{
	public $sm = null;
	protected $_routeMatch = null;
	
	protected $_layoutRow = null;
	protected $_resource = null;
	
	public function setRouteMatch($routeMatch)
	{
		$this->_routeMatch = $routeMatch;
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
		if($layoutDoc->default) {
			return $layoutDoc->type;
		} else {
			return $layoutDoc->getId();
		}
	}
	
	public function getLayoutDoc()
	{
		if($this->_layoutRow == null) {
			$routeName = $this->_routeMatch->getMatchedRouteName();
		
			$factory = $this->sm->get('Core\Mongo\Factory');
			$layoutCo = $factory->_m('Layout');
			
			$layoutDoc = null;
			$routeType = null;
			
			if(strpos($routeName, 'application') === 0) {
				$routeType = substr($routeName, 12);
				if($routeType == "") { //reset to index value
					$routeType = 'index';
				}
			} else if(strpos($routeName, 'user') === 0) {
				$routeType = 'user';
			} else if(strpos($routeName, 'shop') === 0) {
				$routeType = 'shop';
			}
			switch($routeType) {
				case 'index'		: 			//index page
				case 'article'		:			//default single article page
				case 'list'			:			//default article list page
				case 'product'		:			//default single product page
				case 'product-list'	:			//default product list page
				case 'search'		:			//default and only search page for simple site
					$layoutDoc = $layoutCo->addFilter('type', $routeType)
						->fetchOne();
					if(is_null($layoutDoc)) {
						$layoutDoc = $layoutCo->create();
						$layoutDoc->default = 1;
						$layoutDoc->type = $routeType;
						$layoutDoc->save();
					}
					break;
				case 'user-defined': 	//user created page layout
					$layoutDoc = $layoutCo->addFilter('resourceAlias', $this->_routeMatch->getParam('resourceAlias'))
						->fetchOne();
					break;
				case 'user':			//default user page, there should be only one user page layout for now
					$layoutDoc = $layoutCo->addFilter('type', 'user')
						->fetchOne();
					if(is_null($layoutDoc)) {
						$layoutDoc = $layoutCo->create();
						$layoutDoc->default = 1;
						$layoutDoc->type = 'user';
						$layoutDoc->save();
					}
					break;
				case 'shop':
					$layoutDoc = $layoutCo->addFilter('type', 'shop')
						->addFilter('controllerName', $controllerName)
						->fetchOne();
					if(is_null($layoutDoc)) {
						if(in_array($controllerName, array('index', 'order', 'payment-gateway'))) {
							$layoutDoc = $layoutCo->create();
							$layoutDoc->default = 1;
							$layoutDoc->type = 'shop';
							$layoutDoc->controllerName = $controllerName;
							$layoutDoc->save();
						}
					}
					break;
			}
			
			if(is_null($layoutDoc)) {
				throw new Exception("layout settings not found with given layoutName");
			}
			$this->_layoutRow = $layoutDoc;
		}
		return $this->_layoutRow;
	}
	
	public function getResource()
	{
		if(is_null($this->_resource)) {
			$layoutDoc = $this->getLayoutDoc();
			if(is_null($layoutDoc)) {
				$this->_resource = 'none';
				return $this->_resource;
			}
			
			if($layoutDoc->type == 'frontpage' || $layoutDoc->type == 'index' || $layoutDoc->type == 'search') {
				$this->_resource = 'none';
				return $this->_resource;
			}
			
			if($layoutDoc->type == 'user' || $layoutDoc->type == 'shop') {
				return "user and shop page not defined!!";
			}
			
			$factory = $this->sm->get('Core\Mongo\Factory');
			switch($layoutDoc->type) {
				case 'article':
					$co = $factory->_m('Article');
					break;
				case 'list':
					$co = $factory->_m('Group_Item');
					break;
				case 'product':
					$co = $factory->_m('Product');
					break;
				case 'product-list':
					$co = $factory->_m('Group_Item');
					break;
				case 'book':
					$co = $factory->_m('Book');
					break;
			}
			
			if($layoutDoc->default == 1) {
				$id = $this->_routeMatch->getParam('id');
				$this->_resource = $co->find($id);
			} else {
				$resourceAlias = $this->_routeMatch->getParam('resourceAlias');
				$doc = $co->addFilter('alias', $resourceAlias)
						->fetchOne();
				$this->_resource = $doc;
			}
			
			if($this->_resource == null) {
				$this->_resource = 'not-found';
			}
		}
		
		return $this->_resource;
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