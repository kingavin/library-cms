<?php
namespace Document\ServiceAccount;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Domain extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string")  */
	protected $domainName;
	
	/** @ODM\Field(type="boolean")  */
	protected $isActive;
	
	/** @ODM\Field(type="boolean")  */
	protected $isDefault;
	
	/** @ODM\Field(type="string")  */
	protected $redirect;
}