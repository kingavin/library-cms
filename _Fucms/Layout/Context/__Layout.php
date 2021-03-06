<?php
namespace Fucms\Layout\Context;

use Exception;
use Fucms\Layout\ContextAbstract;

class Layout extends ContextAbstract
{	
	public function init($id)
	{
		
		
		$articleCo = $this->factory->_m('Article');
		$articleDoc = $articleCo->find($id);
		if($articleDoc == null) {
			$this->groupId = 0;
		} else {
			$this->groupId = $articleDoc->groupId;
		}
		$groupCo = $this->factory->_m('Group');
		$groupDoc = $groupCo->findArticleGroup();
		$this->groupDoc = $groupDoc;
		$this->trail = $groupDoc->getTrail($this->groupId);
		
		
	}
	
	public function getGroupDoc()
	{
		return $this->groupDoc;
	}
	
	public function getId()
	{
		return $this->groupId;
	}
	
	public function getTrail()
	{
		return array();
	}
	
	public function getType()
	{
		return "layout";
	}
}