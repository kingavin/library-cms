<?php
namespace Fucms\Layout;

class ContextFactory
{
	protected $sm;
	
	public function setServiceManager($sm)
	{
		$this->sm = $sm;
	}
	
	/**
	 * getContext is used to get the context from url type;
	 *
	 * for list pages, it refers to the root group which holds the current list
	 * for item pages, it refers to the root group which holds the item category
	 * for book pages, it refers to the book indexes
	 */
	
	public function getContext($routeMatch)
	{
		$routeName = $routeMatch->getMatchedRouteName();
		$id = $routeMatch->getParam('id');
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
				$pageId = $routeMatch->getParam('pageId');
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
		return $context;
	}
}
	