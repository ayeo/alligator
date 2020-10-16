<?php

declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class Max extends AbstractConstraint
{
    /** @var integer */
    private $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function run($value): bool
    {
        return $value <= $this->max;
    }
}
