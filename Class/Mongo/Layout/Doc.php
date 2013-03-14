<?php
class Class_Mongo_Layout_Doc extends App_Mongo_Db_Document
{
	protected $_field = array(
		'label',
		'type',
		'alias',
		'default',
		'useTpl',
		'tplFileContent'
	);
	
	public function getTplFileContent()
	{
		return $this->_data['tplFileContent'];
	}
}