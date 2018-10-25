<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

use Ayeo\Alligator\MultiErrors;

class ArrayOf extends AbstractConstraint implements MultiErrors
{
    /** @var string[] */
    private $indexes = [];
    /** @var string[] */
    private $allowedValues;

    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    public function run($value): bool
    {
        $this->indexes = [];
        if (is_array($value) === false) {
            return false;
        }

        foreach ($value as $key => $single) {
            if (in_array($single, $this->allowedValues) === false) {
                $this->indexes[] = $key;
            }
        }

        return count($this->indexes) === 0;
    }

    public function getMetadata(): array
    {
        return ['allowedValues' => $this->allowedValues];
    }

    public function getIndexes(): array
    {
        return $this->indexes;
    }


}
