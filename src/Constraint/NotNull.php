<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

use Ayeo\Alligator\CheckNull;

class NotNull extends AbstractConstraint implements CheckNull
{
    public function run($value): bool
    {
        return is_null($value) === false;
    }

    public function getMetadata(): array
    {
        return [];
    }
}
