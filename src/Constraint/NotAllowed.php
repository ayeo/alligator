<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

use Ayeo\Alligator\CheckNull;

class NotAllowed extends AbstractConstraint implements CheckNull
{
    public function run($value): bool
    {
        return empty($value);
    }

    public function getMetadata(): array
    {
        return [];
    }
}
