<?php
namespace Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="attributeset"
 * )
 * 
 * */
class Attributeset extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;

	/** @ODM\Field(type="string")  */
	protected $label;

	/** @ODM\Field(type="string")  */
	protected $type;
	
	/** @ODM\EmbedMany(targetDocument="Document\Attribute")  */
	protected $attributeList = array();
	
	/** @ODM\Field(type="boolean")  */
	protected $isActive;
	
	public function addAttribute($attributeDocument)
	{
		$this->attributeList[] = $attributeDocument;
		return $this;
	}
	
	public function setAttributeList($attributeList)
	{
		$this->attributeList = $attributeList;
		return $this;
	}
	
	public function removeAttribute($id)
	{
		foreach($this->attributeList as $key => $attribute) {
			if($attribute->getId() == $id) {
					unset($this->domains[$key]);
			}
		}
		return false;
	}
}