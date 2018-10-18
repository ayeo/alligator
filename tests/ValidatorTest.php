<?php
namespace Ayeo\Validator2\Tests;

use Ayeo\Validator2\ArrayRules;
use Ayeo\Validator2\Constraint\MinLength;
use Ayeo\Validator2\Constraint\NotNull;
use Ayeo\Validator2\Tests\Mock\Nested;
use Ayeo\Validator2\Tests\Mock\SampleClass;
use Ayeo\Validator2\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckingNullWithNotNull()
    {
        $sample = new SampleClass();
        $rules = new ArrayRules([
            ['name', new NotNull()] //, new MinLength(10)
        ]);

        $validator = new Validator($rules);
        $result = $validator->validate($sample);
        $this->assertFalse($result);
    }

    //should skip validating null field
    public function testCheckingNull()
    {
        $sample = new SampleClass();
        $rules = new ArrayRules([
            ['name', new MinLength(10)]
        ]);

        $validator = new Validator($rules);
        $result = $validator->validate($sample);
        $this->assertTrue($result);
    }

    public function testDefaultValue()
    {
        $sample = new SampleClass();
        $rules = new ArrayRules([
            ['name', new MinLength(10), "Unknown name"]
        ]);

        $validator = new Validator($rules);
        $validator->validate($sample);
        $this->assertEquals("Unknown name", $sample->name);
    }

    public function testNotNull()
    {
        $sample = new SampleClass();
        $rules = new ArrayRules([
            ['name', new NotNull()],
            ['name', new MinLength(10)]
        ]);

        $validator = new Validator($rules);
        $this->assertFalse($validator->validate($sample));
    }

    public function testNestedProperties()
    {
        $sample = new SampleClass();
        $sample->nested = new Nested();
        $sample->nested->name = "short";

        $rules = new ArrayRules([
            ['nested',
                [
                    'name', new MinLength(10)
                ]
            ]
        ]);

        $validator = new Validator($rules);
        $this->assertFalse($validator->validate($sample));
    }
}