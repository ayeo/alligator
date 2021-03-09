<?php

declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

use Ayeo\Alligator\MultiErrors;

class Boolean extends AbstractConstraint
{
    private string $given;

    public function run($value): bool
    {
        $this->given = gettype($value);

        return is_bool($value);
    }

    public function getMetadata(): array
    {
        return [
            'expected' => 'boolean',
            'given' => $this->given
        ];
    }
}
