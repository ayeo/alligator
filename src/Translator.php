<?php declare(strict_types = 1);

namespace Ayeo\Validator2;

use Ayeo\Validator2\Constraint\ArrayOf;
use Ayeo\Validator2\Constraint\ExpectedProperites;
use Ayeo\Validator2\Constraint\Greater;
use Ayeo\Validator2\Constraint\Integer;
use Ayeo\Validator2\Constraint\MinItemsNumber;
use Ayeo\Validator2\Constraint\MinLength;
use Ayeo\Validator2\Constraint\NotAllowed;
use Ayeo\Validator2\Constraint\NotNull;
use Ayeo\Validator2\Constraint\Numeric;
use Ayeo\Validator2\Constraint\ValidRegexp;

class Translator
{
    private $constraints = [
        'not_null' => NotNull::class,
        'integer' => Integer::class,
        'numeric' => Numeric::class,
        'valid_regexp' => ValidRegexp::class,
        'expected_properties' => ExpectedProperites::class,
        'greater' => Greater::class,
        'not_allowed' => NotAllowed::class,
        'min_length' => MinLength::class,
        'array_of' => ArrayOf::class,
        'min_items_number' => MinItemsNumber::class
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
            } elseif ($this->isValidSlug($rules[0])) {
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
        return new Rule($constraint, $rules[1], $rules[2]);
    }
}
