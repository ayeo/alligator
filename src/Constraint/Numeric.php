<?php

declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class Numeric extends AbstractConstraint
{
    /** @var null|int */
    private $precision = null;

    public function __construct(string $precision = null)
    {
        if (isset($precision)) {
            $this->precision = (int)$precision;
        }
    }

    public function run($value): bool
    {
        if (is_numeric($value) === false) {
            return false;
        }

        if (is_null($this->precision)) {
            return true;
        }

        return (float)$value === round((float)$value, $this->precision);
    }
}
