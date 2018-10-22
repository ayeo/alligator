<?php declare(strict_types=1);

namespace Ayeo\Validator2\Constraint;

use Ayeo\Validator2\WholeObjectAware;

class Greater extends AbstractConstraint implements WholeObjectAware
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
        $this->min = $object->$z;

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
