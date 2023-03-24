<?php

declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class Max extends AbstractConstraint
{
    /** @var integer */
    private $max;

    public function __construct($max)
    {
        $this->max = (int)$max;
    }

    public function run($value): bool
    {
        return $value <= $this->max;
    }

    public function getMetadata(): array
    {
        return [
            'limit' => $this->max
        ];
    }
}
