<?php
namespace Fucms\Layout;

use Exception;
use MongoId;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Front implements ServiceLocatorAwareInterface
{
	protected $sm			= null;
	protected $_routeMatch	= null;
	protected $_context		= null;

	public function setRouteMatch($routeMatch)
	{
		$this->_routeMatch = $routeMatch;
	}
	
	public function getRouteMatch()
	{
		return $this->_routeMatch;
	}
	
	/**
	 * getContext is used to get the context from url type;
	 *
	 * for list pages, it refers to the root group which holds the current list
	 * for item pages, it refers to the root group which holds the item category
	 * for book pages, it refers to the book indexes
	 */
	
	public function getContext()
	{
		if(is_null($this->_context)) {
			$routeName = $this->_routeMatch->getMatchedRouteName();
			$id = $this->_routeMatch->getParam('id');
			$presetLayoutDoc = null;
			$factory = $this->sm->get('Core\Mongo\Factory');
			
			if($routeName == 'application/layout') {
				$layoutCo = $factory->_m('Layout');
				$layoutDoc = $layoutCo->addFilter('alias', $id)
					->fetchOne();
				if($layoutDoc == null) {
					throw new Exception('layout not found with layout alias '.$id);
				}
				
				switch($layoutDoc->type) {
					case 'index':
						$routeName = 'application/frontpage';
						break;
					case 'book':
						$routeName = 'application/book';
						break;
					case 'list':
						$routeName = 'application/list';
						break;
					case 'product-list':
						$routeName = 'application/product-list';
						break;
				}
				$id = 0;
				$presetLayoutDoc = $layoutDoc;
			}
			
			switch ($routeName) {
				case 'application':
					$context = new Context\FrontPage($factory);
					$context->init('index');
					break;
				case 'application/frontpage':
					$context = new Context\FrontPage($factory);
					$context->init($id, $presetLayoutDoc);
					break;
				case 'application/search':
					$context = new Context\FrontPage($factory);
					$context->init('search');
					break;
				case 'application/book':
					$bookId = $id;
					$pageId = $this->_routeMatch->getParam('pageId');
					$context = new Context\Book($factory);
					$context->init($bookId, $pageId, $presetLayoutDoc);
					break;
				case 'application/article':
					$context = new Context\Article($factory);
					$context->init($id);
					break;
				case 'application/list':
					$context = new Context\ArticleList($factory);
					$context->init($id, $presetLayoutDoc);
					break;
				case 'application/product':
					$context = new Context\Product($factory);
					$context->init($id);
					break;
				case 'application/product-list':
					$context = new Context\ProductList($factory);
					$context->init($id, $presetLayoutDoc);
					break;
			}
			$this->_context = $context;
		}
		
		return $this->_context;
	}
	
	public function getContextId()
	{
		return $this->_context->getId();
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
	
	public function getLayoutDoc()
	{
		$context = $this->getContext();
		return $context->getLayoutDoc();
	}
	
// 	public function getLayoutDoc()
// 	{
// 		if($this->_layoutRow == null) {
// 			$context = $this->getContext();
// 			$this->_layoutRow = $context->getLayoutDoc();
// 		}
// 		return $this->_layoutRow;
		
// 		if($this->_layoutRow == null) {
// 			$routeName = $this->getRouteName();
			
// 			$factory = $this->sm->get('Core\Mongo\Factory');
// 			$layoutCo = $factory->_m('Layout');
				
// 			$layoutDoc = null;
// 			$routeType = null;
				
// 			if(strpos($routeName, 'application') === 0) {
// 				$routeType = substr($routeName, 12);
// 				if($routeType == "") { //reset to index value
// 					$routeType = 'index';
// 				}
// 			} else if(strpos($routeName, 'user') === 0) {
// 				$routeType = 'user';
// 			} else if(strpos($routeName, 'shop') === 0) {
// 				$routeType = 'shop';
// 			}
// 			switch($routeType) {
// 				case 'index'		: 			//index page
// 				case 'article'		:			//default single article page
// 				case 'list'			:			//default article list page
// 				case 'product'		:			//default single product page
// 				case 'product-list'	:			//default product list page
// 				case 'search'		:			//default and only search page for simple site
// 					$layoutDoc = $layoutCo->addFilter('type', $routeType)
// 					->fetchOne();
// 					if(is_null($layoutDoc)) {
// 						$layoutDoc = $layoutCo->create();
// 						$layoutDoc->default = 1;
// 						$layoutDoc->type = $routeType;
// 						$layoutDoc->save();
// 					}
// 					break;
// 				case 'user-defined': 	//user created page layout
// 					$layoutDoc = $layoutCo->addFilter('resourceAlias', $this->_routeMatch->getParam('resourceAlias'))
// 					->fetchOne();
// 					break;
// 				case 'user':			//default user page, there should be only one user page layout for now
// 					$layoutDoc = $layoutCo->addFilter('type', 'user')
// 					->fetchOne();
// 					if(is_null($layoutDoc)) {
// 						$layoutDoc = $layoutCo->create();
// 						$layoutDoc->default = 1;
// 						$layoutDoc->type = 'user';
// 						$layoutDoc->save();
// 					}
// 					break;
// 				case 'shop':
// 					$layoutDoc = $layoutCo->addFilter('type', 'shop')
// 					->addFilter('controllerName', $controllerName)
// 					->fetchOne();
// 					if(is_null($layoutDoc)) {
// 						if(in_array($controllerName, array('index', 'order', 'payment-gateway'))) {
// 							$layoutDoc = $layoutCo->create();
// 							$layoutDoc->default = 1;
// 							$layoutDoc->type = 'shop';
// 							$layoutDoc->controllerName = $controllerName;
// 							$layoutDoc->save();
// 						}
// 					}
// 					break;
// 			}
				
// 			if(is_null($layoutDoc)) {
// 				throw new Exception("layout settings not found with given layoutName");
// 			}
// 			$this->_layoutRow = $layoutDoc;
// 		}
// 		return $this->_layoutRow;
// 	}

// // 	public function getResourceAlias()
// // 	{
// // 		$r = $this->getContext();
// // 		if($r == 'none' || $r == 'not-found') {
// // 			return null;
// // 		} else {
// // 			if(!empty($r->alias)) {
// // 				return $r->alias;
// // 			}
// // 		}
// // 		return null;
// // 	}

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->sm = $serviceLocator;
	}

	public function getServiceLocator()
	{
		return $this->sm;
	}
}