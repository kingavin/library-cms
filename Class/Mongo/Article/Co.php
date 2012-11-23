<?php
class Class_Mongo_Article_Co extends App_Mongo_Db_Collection
{
	protected $_name = 'article';
	protected $_documentClass = 'Class_Mongo_Article_Doc';
	
	public function statusCount()
	{
		return $this->getCollection()->group(array('status' => 1), array('count' => 0), "function (obj, prev) { prev.count += 1}");
	}
}