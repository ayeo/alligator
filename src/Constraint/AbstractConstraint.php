<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Constraint;

abstract class AbstractConstraint
{
    abstract public function run($value): bool;

    final public function validate($value): bool
    {
        return $this->run($value);
    }

    public function getMetadata(): array
    {
        return [];
    }
}
