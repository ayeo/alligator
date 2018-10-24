<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class NotClassInstance extends AbstractConstraint
{
    /**
     * @var string
     */
    private $className;

    public function __construct($className)
    {
        $this->className = $className;
    }

    public function run($value): void
    {
        $className = $this->className;

        if ($value instanceof $className) {
            $this->addError('must_not_be_instance', $this->className);
        }
    }
}
