<?php

declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

use Ayeo\Alligator\MultiErrors;

class ArrayOfType extends AbstractConstraint implements MultiErrors
{
    /** @var string[] */
    private $indexes = [];

    /** @var string */
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function run($value): bool
    {
        $this->indexes = [];
        if (is_array($value) === false) {
            return false;
        }

        foreach ($value as $key => $single) {
            if (gettype($single) !== $this->type) {
                $this->indexes[] = $key;
            }
        }

        return count($this->indexes) === 0;
    }

    public function getMetadata(): array
    {
        return ['allowedType' => $this->type];
    }

    public function getIndexes(): array
    {
        return $this->indexes;
    }
}
