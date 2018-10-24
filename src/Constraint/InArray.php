<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Constraint;

class InArray extends AbstractConstraint
{
    /** @var string */
    private $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function run($value): bool
    {
        return in_array($value, $this->array);
    }
}
