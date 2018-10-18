<?php

namespace Ayeo\Validator2\Constraint;

class ExpectedProperites extends AbstractConstraint
{
    private $allowedProperites;

    public function __construct(array $allowedProperies)
    {
        $this->allowedProperites = $allowedProperies;
    }

    public function run($value): bool
    {
        return false;
    }

    public function getMetadata(): array
    {
        return ['allowedProperties' => $this->allowedProperites];
    }
}