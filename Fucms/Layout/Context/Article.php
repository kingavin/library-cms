<?php
namespace Fucms\Layout\Context;

use Fucms\Layout\ContextAbstract;

class Article extends ContextAbstract
{
	protected $groupItemId;
	protected $groupAlias;
	protected $groupDoc;
	protected $trail = array();
	
	public function init($id)
	{
		$articleCo = $this->factory->_m('Article');
		$articleDoc = $articleCo->find($id);
		if($articleDoc == null) {
			$this->groupItemId = 0;
		} else {
			$this->groupItemId = $articleDoc->groupId;
		}
		$groupCo = $this->factory->_m('Group');
		$groupDoc = $groupCo->findArticleGroup();
		$this->groupDoc = $groupDoc;
		$this->trail = $groupDoc->getTrail($this->groupItemId);
		
		$layoutCo = $this->factory->_m('Layout');
		$layoutDoc = $layoutCo->addFilter('type', 'article')
			->fetchOne();
		if($layoutDoc == null) {
			$layoutDoc = $this->createDefaultLayout('article');
		}
		$this->layoutDoc = $layoutDoc;
	}
	
	public function getGroupDoc()
	{
		return $this->groupDoc;
	}
	
	public function getGroupItemId()
	{
		return $this->groupItemId;
	}
	
	public function getTrail()
	{
		return $this->trail;
	}
	
	public function getType()
	{
		return "article";
	}
}