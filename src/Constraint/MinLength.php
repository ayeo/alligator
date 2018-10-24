<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class MinLength extends AbstractConstraint
{
    /** @var integer */
    private $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function run($value): bool
    {
        return mb_strlen((string)$value) >= $this->min;
    }

    public function getMetadata(): array
    {
        return [
            'minLength' => $this->min
        ];
    }
}
