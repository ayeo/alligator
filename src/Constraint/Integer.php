<?php
namespace Ayeo\Alligator\Constraint;

class Integer extends AbstractConstraint
{
    public function run($value): bool
    {
        return is_integer($value);
    }
}
