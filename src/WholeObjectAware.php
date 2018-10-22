<?php declare(strict_types=1);

namespace Ayeo\Validator2;

interface WholeObjectAware
{
    public function validateRelated($value, $object): bool;
}