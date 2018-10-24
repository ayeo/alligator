<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Constraint;

class NumericMin extends AbstractConstraint
{
    /** @var float */
	private $min;

	public function __construct(float $min = 0)
	{
	    $this->min = $min;
	}

	public function run($value): bool
	{
	    if (is_numeric($value) === false) {
	        return false;
        }

	    return (float)$value >= $this->min;
	}
}
