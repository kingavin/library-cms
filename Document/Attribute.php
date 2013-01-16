<?php
namespace Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Attribute extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string")  */
	protected $type;
	
	/** @ODM\Field(type="string")  */
	protected $code;
	
	/** @ODM\Field(type="string")  */
	protected $label;
	
	/** @ODM\Field(type="string")  */
	protected $description;
	
	/** @ODM\Field(type="hash")  */
	protected $options;
	
	/** @ODM\Field(type="boolean")  */
	protected $required;
	
	/** @ODM\Field(type="int")  */
	protected $sort;
}