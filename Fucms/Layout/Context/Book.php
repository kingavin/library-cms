<?php
namespace Fucms\Layout\Context;

use MongoId;
use Fucms\Layout\ContextAbstract;

class Book extends ContextAbstract
{
	protected $bookId;
	protected $bookDoc;
	protected $trail = array();
	
	protected $bookLabel;
	
	public function init($bookId, $pageId, $presetLayoutDoc = null)
	{
		$bookCo = $this->dbFactory->_m('Book');
		$bookDoc = $bookCo->addFilter('$or', array(
				array('_id' => new MongoId($bookId)),
				array('alias' => $bookId)
			))->fetchOne();
		
		if($bookDoc == null) {
			$this->bookId = 0;
			$layoutAlias = null;
		} else {
			$this->bookId = $bookDoc->getId();
			$this->bookLabel = $bookDoc->label;
			$layoutAlias = $bookDoc->layoutAlias;
		}
		$this->bookDoc = $bookDoc;
		
		$layoutDoc = null;
		if(is_null($presetLayoutDoc)) {
			$layoutCo = $this->dbFactory->_m('Layout');
			if($layoutAlias != null) {
				//try to load by alias;
				$layoutDoc = $layoutCo->addFilter('type', 'book')
					->addFilter('alias', $layoutAlias)
					->fetchOne();
			}
			if($layoutDoc == null) {
				//load default
				$layoutDoc = $layoutCo->addFilter('type', 'book')
					->addFilter('default', 1)
					->fetchOne();
			}
			if($layoutDoc == null) {
				//create and load default
				$layoutDoc = $this->createDefaultLayout('book');
			}
		} else {
			$layoutDoc = $presetLayoutDoc;
		}
		
		$this->layoutDoc = $layoutDoc;
		$this->routeParams = array('id' => $bookId);
	}
	
	public function getContextDoc()
	{
		return $this->bookDoc;
	}
	
	public function getId()
	{
		return $this->bookId;
	}
	
	public function getBreadcrumb()
	{
		return array();
	}
	
	public function getResourceId()
	{
		return $this->bookId;
	}
	
	public function getTitle()
	{
		return $this->bookLabel;
	}
	
	public function getType()
	{
		return "book";
	}
}