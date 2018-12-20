<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class Greater extends AbstractConstraint
{
    /** @var int */
    private $threshold;

    public function __construct(int $threshold)
    {
        $this->threshold = $threshold;
    }

    public function run($value): bool
    {
        return  $value > $this->threshold;
    }

    public function getMetadata(): array
    {
        return ['threshold' => $this->threshold];
    }
}
