<?php declare(strict_types=1);

namespace Ayeo\Alligator\Tests;

use Ayeo\Alligator\Constraint\ArrayOf;
use Ayeo\Alligator\Error;
use Ayeo\Alligator\ErrorCodesTable;
use Ayeo\Alligator\Rule;
use Ayeo\Alligator\Alligator;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testBasicUsage(): void
    {
        $rules = [
            'values' => new Rule(new ArrayOf(['one', 'two']), 'not_allowed_value')
        ];

        $object = new \stdClass();
        $object->values = ['one', 'two', 'three'];

        $validator = new Alligator();
        $validator->taste($object, $rules);
        $errors = $validator->getErrors();

        $expected = [
            'values' => [
                2 => new Error('not_allowed_value', ['allowedValues' => ['one', 'two']])
            ]
        ];
        $this->assertEquals($expected, $errors);
    }

    public function testBasicCode(): void
    {
        $rules = [
            'values' => new Rule(new ArrayOf(['one', 'two']), 'not_allowed_value')
        ];

        $object = new \stdClass();
        $object->values = ['one', 'two', 'three'];

        $validator = new Alligator();
        $validator->taste($object, $rules);

        $errorCodes = new ErrorCodesTable();
        $errorCodes->add('not_allowed_value', 'Not allowed value');
        $errors = $validator->getErrors($errorCodes);

        $expected = [
            'values' => [
                2 => new Error('not_allowed_value', ['allowedValues' => ['one', 'two']], 'Not allowed value')
            ]
        ];
        $this->assertEquals($expected, $errors);
    }
}
