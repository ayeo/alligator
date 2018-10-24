<?php

namespace Ayeo\Alligator\Constraint;

class ValidRegexp extends AbstractConstraint
{
    public function run($regex): bool
    {
        if (@preg_match($regex, null) === false) {
            return false;
        }

        return true;
    }
}
