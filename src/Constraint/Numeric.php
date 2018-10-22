<?php declare(strict_types = 1);

namespace Ayeo\Validator2\Constraint;

class Numeric extends AbstractConstraint
{
    public function run($value): bool
    {
        return is_numeric($value);
    }
}
