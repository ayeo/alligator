<?php

declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class Min extends AbstractConstraint
{
    /** @var integer */
    private $min;

    public function __construct($min)
    {
        $this->min = (int)$min;
    }

    public function run($value): bool
    {
        return $value >= $this->min;
    }

    public function getMetadata(): array
    {
        return [
            'limit' => $this->min
        ];
    }
}
