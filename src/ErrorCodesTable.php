<?php declare(strict_types=1);

namespace Ayeo\Alligator;

class ErrorCodesTable
{
    /** @var string[] */
    private $codes = [];

    public function add(string $code, string $message): void
    {
        $this->codes[$code] = $message;
    }

    public function getMessage(string $code): string
    {
        return $this->codes[$code];
    }

    public function has(string $code): bool
    {
        return isset($this->codes[$code]);
    }
}
