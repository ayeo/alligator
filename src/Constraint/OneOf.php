<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class OneOf extends AbstractConstraint
{
    private $allowedValues;

    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    public function run($value): bool
    {
        return in_array($value, $this->allowedValues);
    }

    public function getMetadata(): array
    {
        return ['allowedValues' => $this->allowedValues];
    }
}
