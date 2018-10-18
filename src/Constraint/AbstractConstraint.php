<?php
namespace Ayeo\Validator2\Constraint;

use Ayeo\Validator2\CheckNull;

abstract class AbstractConstraint
{
	final public function validate($value): bool
	{
        if (is_null($value)) {
            if ($this instanceof CheckNull === false) {
                return true;
            }
        };

        return $this->run($value);
	}

	abstract public function run($value): bool;

	public function getMetadata(): array
    {
        return [];
    }
}
