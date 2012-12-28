<?php
namespace Fucms\Layout\Context;

use Fucms\Layout\ContextAbstract;

class Product extends ContextAbstract
{
	protected $groupItemId;
	protected $groupAlias;
	protected $groupDoc;
	protected $trail = array();
	
	protected $productLabel;
	
	public function init($id)
	{
		$productCo = $this->factory->_m('Product');
		$productDoc = $productCo->find($id);
		if($productDoc == null) {
			$this->groupItemId = 0;
		} else {
			$this->groupItemId = $productDoc->groupId;
			$this->productLabel = $productDoc->label;
		}
		$groupCo = $this->factory->_m('Group');
		$groupDoc = $groupCo->findProductGroup();
		$this->groupDoc = $groupDoc;
		$this->trail = $groupDoc->getTrail($this->groupItemId);
		
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
	
	public function getGroupItemId()
	{
		return $this->groupItemId;
	}
	
	public function getBreadcrumb()
	{
		if($this->breadcrumb == null) {
			foreach($this->trail as $step) {
				if(empty($step['alias'])) {
					$url = "/product-list-".$step['id'].'/page1.shtml';
				} else {
					$url = "/product-list-".$step['alias'].'/page1.shtml';
				}
	
				$this->breadcrumb[] = array(
					'url' => $url,
					'label' => $step['label']
				);
			}
		}
	
		$this->breadcrumb[] = array(
			'url' => null,
			'label' => $this->productLabel
		);
		
		return $this->breadcrumb;
	}
	
	public function getType()
	{
		return "product";
	}
}