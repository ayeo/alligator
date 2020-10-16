<?php declare(strict_types=1);

namespace Ayeo\Alligator\Constraint;

use Ayeo\Alligator\WholeObjectAware;

class GreaterThanField extends AbstractConstraint implements WholeObjectAware
{
    /** @var string */
    private $fieldName;
    /** @var float */
    private $min;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function validateRelated($value, $object): bool
    {
        if (is_null($value)) {
            return true;
        }

        $z = $this->fieldName;
        if (isset($object->$z)) {
            $this->min = $object->$z;
        } else {
            return true;
        }

        return $value > $this->min;
    }

    public function run($value): bool
    {
        return false;
    }

    public function getMetadata(): array
    {
        return [
            'min' => $this->min
        ];
    }
}