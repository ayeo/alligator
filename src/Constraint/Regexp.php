<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class Regexp extends AbstractConstraint
{
    /** @var string */
    private $pattern;

    /** @var string */
    private $errorMessage;

    public function __construct(string $pattern, ?string $errorMessage = null)
    {
        $this->pattern = $pattern;
        $this->errorMessage = $errorMessage;
    }

    public function run($value): bool
    {
        $result = preg_match($this->pattern, $value);
        if ($result === false || $result === 0) {
            return false;
        }

        return true;
    }
}
