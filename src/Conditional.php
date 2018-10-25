<?php declare(strict_types=1);

namespace Ayeo\Alligator;

class Conditional
{
    private $allowedOperators = []; //todo

    /** @var string */
    private $dependsOn;
    /** @var string */
    private $conditionalValue;
    /** @var array */
    private $rules;
    /** @var string */
    private $operator;

    public function __construct(string $dependsOn, string $operator, string $conditionalValue, array $rules)
    {
        //todo: validate operators
        $this->dependsOn = $dependsOn;
        $this->conditionalValue = $conditionalValue;
        $this->rules = $rules;
        $this->operator = $operator;
    }

    public function getFieldName(): string
    {
        return $this->dependsOn;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function match($value): bool
    {
        switch ($this->operator) {
            case '=':
                return $value == $this->conditionalValue;
            case '!=':
                return $value != $this->conditionalValue;
            case '>':
                return $value > $this->conditionalValue;
            case '>=':
                return $value >= $this->conditionalValue;
            case '<':
                return $value < $this->conditionalValue;
            case '<=':
                return $value <= $this->conditionalValue;
        }

        return false;
    }
}
