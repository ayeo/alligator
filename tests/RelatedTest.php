<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests;

use Ayeo\Alligator\Constraint\GreaterThanField;
use Ayeo\Alligator\Error;
use Ayeo\Alligator\Rule;
use Ayeo\Alligator\Tests\Sample\Range;
use Ayeo\Alligator\Tests\Sample\SampleClass;
use Ayeo\Alligator\Alligator;
use PHPUnit\Framework\TestCase;

class RelatedTest extends TestCase
{
    public function testTest()
    {
        $range = new Range();
        $range->min = 10;
        $range->max = 5;

        $rules = [
            'max' => new Rule(new GreaterThanField('min'), 'Max must be greater than min')
        ];

        $validator = new Alligator();
        $validator->taste($range, $rules);
        $errors = $validator->getErrors();

        $expected = [
            'max' => new Error('Max must be greater than min', ['min' => 10])
        ];

        $this->assertEquals($expected, $errors);
    }

    public function testTest2()
    {
        $range = new Range();
        $range->min = 10;
        $range->max = 50;

        $rules = [
            'max' => new Rule(new GreaterThanField('min'), 'Max must be greater than min')
        ];

        $validator = new Alligator();
        $this->assertTrue($validator->taste($range, $rules));
    }

    public function testWithUnsetMax()
    {
        $range = new Range();
        $range->min = 10;

        $rules = [
            'max' => new Rule(new GreaterThanField('min'), 'Max must be greater than min')
        ];

        $validator = new Alligator();
        $this->assertTrue($validator->taste($range, $rules));
    }

    public function testNested()
    {
        $object = new SampleClass();
        $range = new Range();
        $range->min = 40;
        $range->max = 10;
        $object->nested = $range;

        $rules = [
            'nested' => [
                'max' => new Rule(new GreaterThanField('min'), 'Max must be greater than min')
            ]
        ];

        $expected = [
            'nested' => [
                'max' => new Error('Max must be greater than min', [
                    'min' => 40
                ])
            ]
        ];
        $validator = new Alligator();
        $this->assertFalse($validator->taste($object, $rules));
        $errors = $validator->getErrors();
        $this->assertEquals($expected, $errors);
    }
}