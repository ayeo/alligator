<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Constraint;

use Ayeo\Alligator\CheckNull;

abstract class AbstractConstraint
{
    abstract public function run($value): bool;

    final public function validate($value): bool
    {
        if (is_null($value)) {
            if ($this instanceof CheckNull === false) { //todo: test this case
                return true;
            }
        }

        return $this->run($value);
    }

    public function getMetadata(): array
    {
        return [];
    }
}
