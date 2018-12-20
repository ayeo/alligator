<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests;

use Ayeo\Alligator\Constraint\Greater;
use Ayeo\Alligator\Constraint\MinLength;
use Ayeo\Alligator\Constraint\NotAllowed;
use Ayeo\Alligator\Constraint\NotNull;
use Ayeo\Alligator\Constraint\OneOf;
use Ayeo\Alligator\Error;
use Ayeo\Alligator\Rule;
use Ayeo\Alligator\Tests\Sample\Nested;
use Ayeo\Alligator\Tests\Sample\SampleClass;
use Ayeo\Alligator\Alligator;
use Ayeo\Alligator\Conditional;
use Exception;
use PHPUnit\Framework\TestCase;

class NewFormatTest extends TestCase
{
    public function testTest()
    {
        $nameRule = new Rule(new MinLength(20), 'Name is to short');
        $rules = ['name' => $nameRule];

        $object = new SampleClass();
        $object->name = 'Sample name';

        $validator = new Alligator();
        $validator->taste($object, $rules);
        $errors = $validator->getErrors();

        $expected = [
            'name' => new Error('Name is to short', ['minLength' => 20])
        ];
        $this->assertEquals($expected, $errors);
    }

    public function testTwoRulesToSingleField()
    {
        $nameRule1 = new Rule(new MinLength(4), 'Name is to short');
        $nameRule2 = new Rule(new OneOf(['type1', 'type2', 'type3']), 'Unallowed value');
        $rules = ['name' => [$nameRule1, $nameRule2]];

        $object = new SampleClass();
        $object->name = 'xx';

        $validator = new Alligator();
        $validator->taste($object, $rules);
        $errors = $validator->getErrors();

        $expected = [
            'name' => new Error('Name is to short', ['minLength' => 4])
        ];
        $this->assertEquals($expected, $errors);
    }

    public function testNestedProperties()
    {
        $sample = new SampleClass();
        $sample->nested = new Nested();
        $sample->nested->name = "short";

        $nameRule = new Rule(new MinLength(20), 'Name is to short');
        $rules = ['nested' => ['name' => $nameRule]];

        $validator = new Alligator();
        $this->assertFalse($validator->taste($sample, $rules));
        $expected = [
            'nested' => [
                'name' => new Error('Name is to short', ['minLength' => 20])
            ]
        ];
        $errors = $validator->getErrors();
        $this->assertEquals($expected, $errors);
    }

    public function tesDoubletNestedProperties()
    {
        $sample = new SampleClass();
        $sample->nested = new SampleClass();
        $sample->nested->nested = new Nested();
        $sample->nested->nested->name = 'Nested name';

        $nameRule = new Rule(new MinLength(20), 'Name is to short');
        $rules = ['nested' => ['nested' => ['name' => $nameRule]]];

        $validator = new Alligator();
        $this->assertFalse($validator->taste($sample, $rules));
        $expected = [
            'nested' => [
                'nested' => [
                    'name' => new Error('Name is to short', ['minLength' => 20])
                ]
            ]
        ];
        $errors = $validator->getErrors();
        $this->assertEquals($expected, $errors);
    }

    public function testCheckingNullWithNotNull()
    {
        $sample = new SampleClass();
        $sample->name = 'special-value';
        $sample->nested['min'] = 'the only valid';

        $rules = [
            'name' => new Rule(new NotNull(), 'Name must not be null'),
            'nested' => [
                new Conditional('name', '=', 'special-value', [
                    'min' => new Rule(new OneOf(['the only valid']), 'Invalid value')
                ])
            ]
        ];

        $validator = new Alligator();
        $result = $validator->taste($sample, $rules);
        $this->assertTrue($result);
    }

    public function testCheckingNullWithNotNull2()
    {
        $sample = new SampleClass();
        $sample->name = 'special-value';
        $sample->nested['min'] = 'expected';

        $rule = new Rule(new OneOf(['the only valid']), 'Invalid value');
        $rules = [
            'name' => new Rule(new NotNull(), 'Name must not be null'),
            'nested' => [
                new Conditional('name', '=', 'special-value',
                    ['min' => $rule]
                )
            ]
        ];

        $validator = new Alligator();
        $this->assertFalse($validator->taste($sample, $rules));
        $expected = [
            'nested' => [
                'min' => new Error('Invalid value', ['allowedValues' => ['the only valid']])
            ]
        ];
        $errors = $validator->getErrors();
        $this->assertEquals($expected, $errors);
    }

    public function testConditional()
    {
        $sample = new SampleClass();
        $sample->name = 'a';
        $sample->nested = new Nested();
        $sample->nested->name = 'b';

        $rules = [
            'nested' => [
                new Conditional('name', '=', 'a',
                    ['name' => new Rule(new OneOf(['b']), 'Invalid value')]
                )
            ]
        ];
        $validator = new Alligator();
        $this->assertTrue($validator->taste($sample, $rules));
        $expected = [];
        $errors = $validator->getErrors();
        $this->assertEquals($expected, $errors);
    }

    public function testConditional2()
    {
        $sample = new SampleClass();
        $sample->name = 'a';
        $sample->nested = new Nested();
        $sample->nested->name = 'a';

        $rule = new Rule(new OneOf(['b']), 'Invalid value');
        $rules = [
            'nested' => [
                new Conditional('name', '=', 'a',
                    ['name' => $rule]
                )
            ]
        ];

        $validator = new Alligator();
        $this->assertFalse($validator->taste($sample, $rules));
        $expected = [
            'nested' => [
                'name' => new Error('Invalid value', ['allowedValues' => ['b']])
            ]
        ];
        $errors = $validator->getErrors();
        $this->assertEquals($expected, $errors);
    }

    public function testNotAllowed()
    {
        $rules = [
            'description' => [
                new Conditional('name', '=', 'test', [
                    '' => new Rule(new NotAllowed(), '1500')
                ])
        ]];

        $object = new \stdClass();
        $object->name = 'test';
        $object->description = 'tralalala';

        $validator = new Alligator();
        $validator->taste($object, $rules);
        $errors = $validator->getErrors();

        $expected = [
            'description' => new Error('1500', [])
        ];
        $this->assertEquals($expected, $errors);
    }

    /**
     * @throws Exception
     */
    public function testGreaterConstraint(): void //fixme: move to constraint test
    {
        $rules = ['size' => [new Rule(new Greater(10), 'too_low')]];

        $object = new \stdClass();
        $object->size = 5;

        $validator = new Alligator();
        $validator->taste($object, $rules);
        $errors = $validator->getErrors();

        $expected = ['size' => new Error('too_low', ['threshold' => 10])];
        $this->assertEquals($expected, $errors);
    }

    public function testNotAllowedWithObject()
    {
        $rules = [
            'description' => [
                new Conditional('name', '=', 'test', [
                    '' => new Rule(new NotAllowed(), '1500')
                ])
            ]];

        $object = new \stdClass();
        $object->name = 'test';
        $object->description = new \stdClass();
        $object->description->body = 'Tralalalal';

        $validator = new Alligator();
        $validator->taste($object, $rules);
        $errors = $validator->getErrors();

        $expected = [
            'description' => new Error('1500', [])
        ];
        $this->assertEquals($expected, $errors);
    }
}