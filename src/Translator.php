<?php declare(strict_types = 1);

namespace Ayeo\Alligator;

use Ayeo\Alligator\Constraint\ArrayOf;
use Ayeo\Alligator\Constraint\ExpectedProperites;
use Ayeo\Alligator\Constraint\Greater;
use Ayeo\Alligator\Constraint\Integer;
use Ayeo\Alligator\Constraint\MinItemsNumber;
use Ayeo\Alligator\Constraint\MinLength;
use Ayeo\Alligator\Constraint\NotAllowed;
use Ayeo\Alligator\Constraint\NotNull;
use Ayeo\Alligator\Constraint\Numeric;
use Ayeo\Alligator\Constraint\ValidRegexp;

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
