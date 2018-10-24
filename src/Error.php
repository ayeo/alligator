<?php
namespace Ayeo\Alligator;

class Error
{
    //fixme: use toArray instead public props
    /** @var array */
    public $metadata;
    /** @var string */
    public $code;

    public $message;

    public function __construct(string $code, array $metadata, ?string $message = null)
    {
        $this->metadata = $metadata;
        $this->code = $code;
        $this->message = $message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
