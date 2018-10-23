<?php

namespace Ayeo\Validator2\Tests;

use Ayeo\Validator2\Constraint\MinLength;
use Ayeo\Validator2\Constraint\NotAllowed;
use Ayeo\Validator2\Constraint\NotNull;
use Ayeo\Validator2\Constraint\OneOf;
use Ayeo\Validator2\Error;
use Ayeo\Validator2\Rule;
use Ayeo\Validator2\Tests\Mock\Nested;
use Ayeo\Validator2\Tests\Mock\SampleClass;
use Ayeo\Validator2\Validator;
use Ayeo\Validator2\Conditional;
use PHPUnit\Framework\TestCase;

class NewFormatTest extends TestCase
{
    public function testTest()
    {
        $nameRule = new Rule(new MinLength(20), 'Name is to short');
        $rules = ['name' => $nameRule];

        $object = new SampleClass();
        $object->name = 'Sample name';

        $validator = new Validator();
        $validator->validate($object, $rules);
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

        $validator = new Validator();
        $validator->validate($object, $rules);
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

        $validator = new Validator();
        $this->assertFalse($validator->validate($sample, $rules));
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

        $validator = new Validator();
        $this->assertFalse($validator->validate($sample, $rules));
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
                new Conditional('name', 'special-value', [
                    'min' => new Rule(new OneOf(['the only valid']), 'Invalid value')
                ])
            ]
        ];

        $validator = new Validator();
        $result = $validator->validate($sample, $rules);
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
                new Conditional('name', 'special-value',
                    ['min' => $rule]
                )
            ]
        ];

        $validator = new Validator();
        $this->assertFalse($validator->validate($sample, $rules));
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
                new Conditional('name', 'a',
                    ['name' => new Rule(new OneOf(['b']), 'Invalid value')]
                )
            ]
        ];
        $validator = new Validator();
        $this->assertTrue($validator->validate($sample, $rules));
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
                new Conditional('name', 'a',
                    ['name' => $rule]
                )
            ]
        ];

        $validator = new Validator();
        $this->assertFalse($validator->validate($sample, $rules));
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
                new Conditional('name', 'test', [
                    '' => new Rule(new NotAllowed(), '1500')
                ])
        ]];

        $object = new \stdClass();
        $object->name = 'test';
        $object->description = 'tralalala';

        $validator = new Validator();
        $validator->validate($object, $rules);
        $errors = $validator->getErrors();

        $expected = [
            'description' => new Error('1500', [])
        ];
        $this->assertEquals($expected, $errors);
    }

    public function testNotAllowedWithObject()
    {
        $rules = [
            'description' => [
                new Conditional('name', 'test', [
                    '' => new Rule(new NotAllowed(), '1500')
                ])
            ]];

        $object = new \stdClass();
        $object->name = 'test';
        $object->description = new \stdClass();
        $object->description->body = 'Tralalalal';

        $validator = new Validator();
        $validator->validate($object, $rules);
        $errors = $validator->getErrors();

        $expected = [
            'description' => new Error('1500', [])
        ];
        $this->assertEquals($expected, $errors);
    }
}