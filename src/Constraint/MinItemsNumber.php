<?php declare(strict_types = 1);

namespace Ayeo\Validator2\Constraint;

use Ayeo\Validator2\CheckNull;

class MinItemsNumber extends AbstractConstraint implements CheckNull
{
	/** @var int */
	private $min;

	public function __construct(string $min)
	{
		$this->min = (int)$min;
	}

	public function run($value): bool
	{
	    if (is_array($value) === false ) {
	        return false;
        }
		return count($value) >= $this->min;
	}

	public function getMetadata(): array
    {
        return ['minItemsNo' => $this->min];
    }
}
