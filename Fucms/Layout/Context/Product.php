<?php
namespace Fucms\Layout\Context;

use Fucms\Layout\ContextAbstract;

class Product extends ContextAbstract
{
	protected $groupId;
	protected $groupAlias;
	protected $groupDoc;
	protected $trail = array();
	
	public function init($id)
	{
		$productCo = $this->factory->_m('Product');
		$productDoc = $productCo->find($id);
		if($productDoc == null) {
			$this->groupId = 0;
		} else {
			$this->groupId = $productDoc->groupId;
		}
		$groupCo = $this->factory->_m('Group');
		$groupDoc = $groupCo->findProductGroup();
		$this->groupDoc = $groupDoc;
		$this->trail = $groupDoc->getTrail($this->groupId);
		
		$layoutCo = $this->factory->_m('Layout');
		$layoutDoc = $layoutCo->addFilter('type', 'product')
			->fetchOne();
		if($layoutDoc == null) {
			$layoutDoc = $this->createDefaultLayout('product');
		}
		$this->layoutDoc = $layoutDoc;
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
		return $this->trail;
	}
	
	public function getType()
	{
		return "product";
	}
}