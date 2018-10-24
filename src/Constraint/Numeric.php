<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Constraint;

class Numeric extends AbstractConstraint
{
    public function run($value): bool
    {
        return is_numeric($value);
    }
}
