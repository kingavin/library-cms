<?php
namespace Fucms\Layout\Context;

use Fucms\Layout\ContextAbstract;

class FrontPage extends ContextAbstract
{
	protected $type;
	
	public function init($id, $presetLayoutDoc = null)
	{
		$layoutCo = $this->dbFactory->_m('Layout');
		
		if($presetLayoutDoc == null) {
			if($id == 'index') {
				$layoutDoc = $layoutCo->addFilter('type', 'index')
					->addFilter('default', 1)
					->fetchOne();
				if($layoutDoc == null) {
					$layoutDoc = $this->createDefaultLayout($id);
				}
				
			} else {
				//load by alias
				$layoutDoc = $layoutCo->addFilter('type', 'index')
					->addFilter('alias', $id)
					->fetchOne();
				if($layoutDoc == null) {
					throw new Exception('layout not found with alias : '.$id);
				}
			}
		} else {
			$layoutDoc = $presetLayoutDoc;
		}
		
		$this->layoutDoc = $layoutDoc;
	}
	
	public function getBreadcrumb()
	{
		return null;
	}
	
	public function getResourceId()
	{
		return $this->layoutDoc->getId();
	}
	
	public function getTitle()
	{
		return $this->layoutDoc->label;
	}
	
	public function getType()
	{
		return "frontpage";
	}
}