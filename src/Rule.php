<?php

namespace Ayeo\Validator2;

class Rule
{
    /** @var Constraint\AbstractConstraint */
    private $constraint;
    /** @var string */
    private $errorCode;

    public function __construct(Constraint\AbstractConstraint $constraint, string $errorCode)
    {
        $this->constraint = $constraint;
        $this->errorCode = $errorCode;
    }

    public function getConstraint(): Constraint\AbstractConstraint
    {
        return $this->constraint;
    }

    public function getCode(): string
    {
        return $this->errorCode;
    }
}
