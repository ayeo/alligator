<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class ExpectedProperites extends AbstractConstraint
{
    private $allowedProperites;

    public function __construct(array $allowedProperies)
    {
        $this->allowedProperites = $allowedProperies;
    }

    public function run($value): bool
    {
        return in_array($value, $this->allowedProperites, true);
    }

    public function getMetadata(): array
    {
        return ['allowedProperties' => $this->allowedProperites];
    }
}
