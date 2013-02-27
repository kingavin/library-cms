<?php
class Class_Mongo_Product_Doc extends App_Mongo_Entity_Doc
{
	protected $_field = array(
			'attributesetId',
			'fulltext',
			'groupId',
			'introicon',
			'introtext',
			'metakey',
			'label',
			'name',
			'sku',
			'origPrice',
			'price',
			'showWhere',
			'weight',
			'graphics',
			'attachmentFiles',
			'status',
			'attributes',
			'attributesLabel'
	);
	
	public function setAttachments($urlArr, $nameArr, $typeArr)
	{
		if(count($urlArr) == 0) {
			return true;
		}
		if(count($urlArr) != count($nameArr) || count($urlArr) != count($typeArr)) {
			throw new Exception('attachment count does not match each other!');
		}

		$attachment = array();
		foreach($typeArr as $key => $type) {
			$attachment[] = array('filetype' => $type, 'filename' => $nameArr[$key], 'urlname' => $urlArr[$key]);
		}
		$this->attachment = $attachment;
	}

	public function toggleTrash()
	{
		if($this->status == 'trash') {
			$this->status = 'publish';
		} else {
			$this->status = 'trash';
		}
		$this->save();
	}
}