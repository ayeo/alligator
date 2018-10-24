<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

class Length extends AbstractConstraint
{
    /**
     * @var integer
     */
    private $length;

    /**
     * @param int $length
     */
    public function __construct(int $length = 0)
    {
        $this->length = $length;
    }

    public function run($value): void
    {
        if (mb_strlen($value) !== $this->length) {
            $this->addError('must_be_exactly_char_length', $this->length);
        }
    }
}
