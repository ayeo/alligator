<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Constraint;

class IsArray extends AbstractConstraint
{
	public function run($value): bool
	{
		return is_array($value);
	}
}
