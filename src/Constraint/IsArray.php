<?php

namespace Ayeo\Validator2\Constraint;


class IsArray extends AbstractConstraint
{
	public function run($value)
	{
		if (is_array($value) === false)
		{
			$this->addError('Given value is not an array');
		}
	}
}