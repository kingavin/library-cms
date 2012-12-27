<?php
namespace Fucms\Layout\Context;

use MongoId;
use Fucms\Layout\ContextAbstract;

class ArticleList extends ContextAbstract
{
	protected $groupItemId;
	protected $routeParams = array();
	protected $groupItemDoc;
	protected $groupDoc;
	protected $trail = array();
	
	public function init($id, $presetLayoutDoc = null)
	{
		$groupItemCo = $this->factory->_m('Group_Item');
		$groupItemDoc = $groupItemCo->addFilter('$or', array(
				array('_id' => new MongoId($id)),
				array('alias' => $id)
			))->fetchOne();
		if($groupItemDoc == null) {
			$groupItemId = 0;
		} else {
			$groupItemId = $groupItemDoc->getId();
		}
		$groupCo = $this->factory->_m('Group');
		$groupDoc = $groupCo->findArticleGroup();
		$this->groupItemId = $groupItemId;
		$this->groupItemDoc = $groupItemDoc;
		$this->groupDoc = $groupDoc;
		$this->trail = $groupDoc->getTrail($groupItemId);
		
		$layoutAlias = null;
		foreach($this->trail as $seek) {
			if(isset( $seek['layoutAlias'])) {
				$layoutAlias = $seek['layoutAlias'];
			}
		}
		$this->contextId = $layoutAlias;
		
		$layoutDoc = null;
		if(is_null($presetLayoutDoc)) {
			$layoutCo = $this->factory->_m('Layout');
			if($layoutAlias != null) {
				//try to load by alias;
				$layoutDoc = $layoutCo->addFilter('type', 'list')
					->addFilter('alias', $layoutAlias)
					->fetchOne();
			}
			if($layoutDoc == null) {
				//load default
				$layoutDoc = $layoutCo->addFilter('type', 'list')
					->addFilter('default', 1)
					->fetchOne();
			}
			if($layoutDoc == null) {
				//create and load default
				$layoutDoc = $this->createDefaultLayout('list');
			}
		} else {
			$layoutDoc = $presetLayoutDoc;
		}
		
		$this->layoutDoc = $layoutDoc;
		$this->routeParams = array('id' => $id);
	}
	
	public function getRouteParams()
	{
		return $this->routeParams;
	}
	
	public function getGroupItemId()
	{
		return $this->groupItemId;
	}
	
	public function getGroupItemDoc()
	{
		return $this->groupItemDoc;
	}
	
	public function getGroupDoc()
	{
		return $this->groupDoc;
	}
	
	public function getTrail()
	{
		return $this->trail;
	}
	
	public function getType()
	{
		return "article-list";
	}
}