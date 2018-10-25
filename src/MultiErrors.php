<?php declare(strict_types=1);

namespace Ayeo\Alligator;

interface MultiErrors
{
    /** @var string[] */
    public function getIndexes(): array;
}
