<?php
namespace Ayeo\Validator2\Constraint;

class Integer extends AbstractConstraint
{
    public function run($value)
    {
        if (is_null($this->getFieldValue())) {
            return;
        }

        if (is_integer($value) === false) {
            $this->addError('must_be_integer');
        }
    }
}