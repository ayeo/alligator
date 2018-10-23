<?php declare(strict_types = 1);

namespace Ayeo\Validator2\Constraint;

use Ayeo\Validator2\CheckNull;

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
