<?php declare(strict_types=1);

namespace Ayeo\Alligator;

use Ayeo\Alligator\Constraint;

class Translator
{
    private $constraints = [
        'not_null' => Constraint\NotNull::class,
        'integer' => Constraint\Integer::class,
        'numeric' => Constraint\Numeric::class,
        'valid_regexp' => Constraint\ValidRegexp::class,
        'expected_properties' => Constraint\ExpectedProperites::class,
        'greater' => Constraint\Greater::class,
        'not_allowed' => Constraint\NotAllowed::class,
        'min_length' => Constraint\MinLength::class,
        'one_of' => Constraint\OneOf::class,
        'array_of' => Constraint\ArrayOf::class,
        'min_items_number' => Constraint\MinItemsNumber::class
    ];

    private function isValidSlug($slug): bool
    {
        return is_string($slug); //&& array key exists
    }

    private function isConditional($name): bool
    {
        return is_string($name) && count(explode('=', $name)) > 1;
    }

    private function buildConstraint(string $slug): Constraint\AbstractConstraint
    {
        $data = explode(':', $slug);
        $className = $this->constraints[array_shift($data)];

        foreach ($data as $key => $value) {
            if (preg_match('#^\[(.*)\]$#', $value, $result)) {
                $data[$key] = explode(',', $result[1]);
            } else {
                $data[$key] = $value;
            }
        }

        return new $className(...$data);
    }

    public function translate(array $input, array &$result = []): array
    {
        foreach ($input as $name => $rules) {
            if ($this->isConditional($name)) {
                $data = explode('=', $name);
                $result[] = new Conditional($data[0], $data[1], $this->translate($rules));
            } elseif (isset($rules[0]) && $this->isValidSlug($rules[0])) {
                $rule = $this->buildRule($rules);
                $result[$name] = $rule;
            } else {
                $result[$name] = [];
                $this->translate($rules, $result[$name]);
            }
        }

        return $result;
    }

    private function buildRule($rules): Rule
    {
        $slug = $rules[0];
        $constraint = $this->buildConstraint($slug);
        return new Rule($constraint, $rules[1], $rules[2] ?? '');
    }
}
