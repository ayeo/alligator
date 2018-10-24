<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class MaxLength extends AbstractConstraint
{
    /** @var integer */
    private $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function run($value): bool
    {
        return mb_strlen((string)$value) <= $this->max;
    }
}
