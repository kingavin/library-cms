<?php
namespace Fucms\Brick\Service;

use Zend\Mvc\MvcEvent;
use Fucms\Brick\Register;

class RegisterConfigAdmin
{
	public function configRegister(Register $register)
	{
		$register->registerBrick(array(
				'Admin\ActionTitle',
				'Admin\ActionMenu'
		));
	}
}