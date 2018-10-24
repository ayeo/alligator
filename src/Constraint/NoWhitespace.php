<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class NoWhitespace extends AbstractConstraint
{
    public function run($value): void
    {
        $parsed = preg_replace('/\s+/', '', $value);
        if ($parsed !== $value)         {
            $this->addError('must_not_contains_whitespace');
        }
    }
}
