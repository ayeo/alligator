<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class NonEmpty extends AbstractConstraint
{
    public function run($value): bool
    {
        if ($value === 0 || $value === '0') {
            return true;
        }

        return empty($value) === false;
    }
}
