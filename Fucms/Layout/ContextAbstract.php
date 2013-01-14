<?php
namespace Fucms\Layout;

use Exception;
use MongoId;

abstract class ContextAbstract
{
	protected $dbFactory;
	protected $layoutDoc;
	protected $trail;
	protected $breadcrumb;
	protected $contextId = null;
	
	public function __construct($dbFactory)
	{
		$this->dbFactory = $dbFactory;
	}
	
	protected function createDefaultLayout($type)
	{
		$layoutCo= $this->dbFactory->_m('Layout');
		
		$layoutDoc = $layoutCo->create();
		$layoutDoc->type = $type;
		$layoutDoc->default = 1;
		$layoutDoc->save();
		
		return $layoutDoc;
	}
	
	public function getLayoutDoc()
	{
		return $this->layoutDoc;
	}
	
	public function getContextId()
	{
		return $this->contextId;
	}
	
	public function getTrail()
	{
		return $this->trail;
	}
	
	public function getResourceDoc()
	{
		return null;
	}
	
	abstract public function getResourceId();
	
	abstract public function getTitle();
	
	abstract public function getType();
}