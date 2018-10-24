<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class ArrayOf extends AbstractConstraint
{
    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    public function run($value): bool
    {
        if (is_array($value) === false) {
            return false;
        }

        foreach ($value as $single) {
            if (in_array($single, $this->allowedValues) === false) {
                return false;
            }
        }

        return true;
    }

    public function getMetadata(): array
    {
        return ['allowedValues' => $this->allowedValues];
    }
}
