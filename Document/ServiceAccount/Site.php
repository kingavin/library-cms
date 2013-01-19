<?php
namespace Document\ServiceAccount;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		db="service_account",
 * 		collection="site"
 * )
 * 
 * */
class Site extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;

	/** @ODM\Field(type="string")  */
	protected $organizationCode;

	/** @ODM\Field(type="string")  */
	protected $remoteSiteId;
	
	/** @ODM\Field(type="string")  */
	protected $globalSiteId;
	
	/** @ODM\EmbedMany(targetDocument="Document\ServiceAccount\Domain")  */
	protected $domains = array();
	
	/** @ODM\Field(type="boolean")  */
	protected $isActive;
	
	public function addDomain($domainDocument)
	{
		$this->domains[] = $domainDocument;
		return $this;
	}
	
	public function removeDomain($id)
	{
		foreach($this->domains as $key => $domainDoc) {
			if($domainDoc->getId() == $id) {
				if($domainDoc->getIsDefault()) {
					return false;
				} else {
					unset($this->domains[$key]);
					return true;
				}
			}
		}
		return false;
	}
}