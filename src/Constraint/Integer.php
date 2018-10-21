<?php
namespace Ayeo\Validator2\Constraint;

class Integer extends AbstractConstraint
{
    public function run($value): bool
    {
        return is_integer($value);
    }
}