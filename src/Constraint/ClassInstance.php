<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class ClassInstance extends AbstractConstraint
{
    /** @var string */
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function run($value): bool
    {
        $className = $this->className;

        return $value instanceof $className;
    }
}
