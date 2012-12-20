<?php
namespace Fucms\Brick\Service;

use Zend\Mvc\MvcEvent;
use Fucms\Brick\Register;

class RegisterConfig
{
	protected $_layoutDoc = null;
	protected $_factory = null;

	public function __construct($layoutDoc, $factory)
	{
		$this->_layoutDoc = $layoutDoc;
		$this->_factory = $factory;
	}

	public function configRegister(Register $register)
	{
		$layoutDoc = $this->_layoutDoc;
		$layoutId = $layoutDoc->getId();
		$co = $this->_factory->_m('Brick');
			
		if($layoutDoc->hideHead == "1" && $layoutDoc->HideTail == "1") {
			$co->addFilter('layoutId', $layoutId)
			->addFilter('active', 1)
			->sort('sort');
		} else {
			$co->addFilter('$or', array(
					array('layoutId' => $layoutId),
					array('layoutId' => '0'))
			)
			->addFilter('active', 1)
			->sort('sort');
		}
		$brickDocs = $co->fetchDoc();

		foreach($brickDocs as $brick) {
			$register->registerBrick($brick);
		}
	}
}