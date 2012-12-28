<?php
namespace Fucms\Layout\Context;

use Fucms\Layout\ContextAbstract;

class Article extends ContextAbstract
{
	protected $groupItemId;
	protected $groupAlias;
	protected $groupDoc;
	protected $trail = array();
	
	protected $articleLabel;
	
	public function init($id)
	{
		$articleCo = $this->factory->_m('Article');
		$articleDoc = $articleCo->find($id);
		if($articleDoc == null) {
			$this->groupItemId = 0;
		} else {
			$this->groupItemId = $articleDoc->groupId;
			$this->articleLabel = $articleDoc->label;
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
	
	public function getBreadcrumb()
	{
		if($this->breadcrumb == null) {
			foreach($this->trail as $step) {
				if(empty($step['alias'])) {
					$url = "/list-".$step['id'].'/page1.shtml';
				} else {
					$url = "/list-".$step['alias'].'/page1.shtml';
				}
	
				$this->breadcrumb[] = array(
					'url' => $url,
					'label' => $step['label']
				);
			}
		}
		
		$this->breadcrumb[] = array(
			'url' => null,
			'label' => $this->articleLabel
		);
		
		return $this->breadcrumb;
	}
	
	public function getType()
	{
		return "article";
	}
}