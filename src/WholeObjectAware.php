<?php declare(strict_types=1);

namespace Ayeo\Alligator;

interface WholeObjectAware
{
    public function validateRelated($value, $object): bool;
}
