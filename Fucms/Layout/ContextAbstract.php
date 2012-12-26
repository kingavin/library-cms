<?php
namespace Fucms\Layout;

use Exception;
use MongoId;

abstract class ContextAbstract
{
	protected $factory;
	protected $layoutDoc;
	protected $contextId = null;
	
	public function __construct($factory)
	{
		$this->factory = $factory;
	}
	
	protected function createDefaultLayout($type)
	{
		$layoutCo= $this->factory->_m('Layout');
		
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
	
	abstract public function getType();
}