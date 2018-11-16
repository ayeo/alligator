<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class MinItemsNumber extends AbstractConstraint
{
    /** @var int */
    private $min;

    public function __construct(string $min)
    {
        $this->min = (int)$min;
    }

    public function run($value): bool
    {
        if (is_array($value) === false) {
            return false;
        }
        return count($value) >= $this->min;
    }

    public function getMetadata(): array
    {
        return ['minItemsNo' => $this->min];
    }
}
