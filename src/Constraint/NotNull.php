<?php
namespace Ayeo\Validator2\Constraint;

use Ayeo\Validator2\CheckNull;

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
