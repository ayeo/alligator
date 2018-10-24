<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class ValidRegexp extends AbstractConstraint
{
    public function run($regex): bool
    {
        if (@preg_match((string)$regex, '') === false) {
            return false;
        }

        return true;
    }
}
