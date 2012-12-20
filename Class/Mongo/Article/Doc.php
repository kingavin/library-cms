<?php
class Class_Mongo_Article_Doc extends App_Mongo_Entity_Doc
{
	protected $_field = array(
			'groupId',
			'label',
			'link',
			'introtext',
			'metakey',
			'introicon',
			'fulltext',
			'created',
			'createdBy',
			'createdByAlias',
			'modified',
			'modifiedBy',
			'modifiedByAlias',
			'sort',
			'hits',
			'featured',
			'reference',
			'attachmentFiles',
			'status'
	);

	public function setAttachments($urlArr, $nameArr, $typeArr)
	{
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