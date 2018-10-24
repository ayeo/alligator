<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests;

use Ayeo\Alligator\Conditional;
use Ayeo\Alligator\Constraint\ExpectedProperites;
use Ayeo\Alligator\Constraint\Greater;
use Ayeo\Alligator\Constraint\Integer;
use Ayeo\Alligator\Constraint\NotNull;
use Ayeo\Alligator\Constraint\ValidRegexp;
use Ayeo\Alligator\Rule;
use Ayeo\Alligator\Translator;
use PHPUnit\Framework\TestCase;

class ConfigTranslateTest extends TestCase
{
    public function testSimple(): void
    {
        $input = ['symbol' => ['not_null', '0001', 'Required']];
        $expectedOutput = ['symbol' =>  new Rule(new NotNull(), '0001', 'Required')];
        $translator = new Translator();
        $this->assertEquals($expectedOutput, $translator->translate($input));
    }

    public function testMulti(): void //no code here
    {
        $input = [
            'symbol' => [
                ['not_null', 'not_null'],
                ['integer', 'must_be_int']
            ]
        ];
        $expectedOutput = [
            'symbol' =>  [
                new Rule(new NotNull(), 'not_null'),
                new Rule(new Integer(), 'must_be_int'),
            ]
        ];
        $translator = new Translator();
        $this->assertEquals($expectedOutput, $translator->translate($input));
    }

    public function testConditional(): void
    {
        $input = [
            'nested' => [
                'role=moderator' => [
                    'salary' => ['not_null', 'Required'],
                ]
            ]
        ];
        $expectedOutput = [
            'nested' =>  [
                new Conditional('role', 'moderator', [
                    'salary' => new Rule(new NotNull(), 'Required'),
                ])

            ]
        ];
        $translator = new Translator();
        $result = $translator->translate($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testWithSingleStringParameter(): void
    {
        $input = ['max' => ['greater:min', 'Max must be greater than min']];
        $expectedOutput = [
            'max' =>  new Rule(new Greater('min'), 'Max must be greater than min')
        ];
        $translator = new Translator();
        $result = $translator->translate($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testWithSingleArrayParameter(): void
    {
        $input = ['*' => ['expected_properties:[min,max]', 'Not allowed']];
        $expectedOutput = [
            '*' =>  new Rule(new ExpectedProperites(['min', 'max']), 'Not allowed')
        ];
        $translator = new Translator();
        $result = $translator->translate($input);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testComplex(): void
    {
        $input = [
            'symbol' => ['not_null', 'Required', '0001'],
            'type' => ['not_null', 'Required', '0001'],
            'constraints' => [
                'type=text' => [
                    '*' => ['expected_properties:[pattern]', 'Redundant', '0002'],
                    'pattern' => ['valid_regexp', 'Invalid pattern', '1001']
                ],
                'type=integer' => [
                    '*' => ['expected_properties:[min,max,step]', 'Redundant', '0002'],
                    'min' => ['integer', 'Must be integer', '1002'],
                    'max' => [
                        ['integer', 'Must be integer', '1002'],
                        ['greater:min', 'Max must be greater than min', '1003'],
                    ],
                    'step' => ['integer', 'Must be integer', '1002']
                ]
            ]
        ];
        $expectedOutput = [
            'symbol' =>  new Rule(new NotNull(), 'Required', '0001'),
            'type' => new Rule(new NotNull(), 'Required', '0001'),
            'constraints' => [
                new Conditional('type', 'text', [
                    '*' => new Rule(new ExpectedProperites(['pattern']), 'Redundant', '0002'),
                    'pattern' => new Rule(new ValidRegexp(), 'Invalid pattern', '1001')
                ]),
                new Conditional('type', 'integer', [
                    '*' => new Rule(new ExpectedProperites(['min', 'max', 'step']), 'Redundant', '0002'),
                    'min' => new Rule(new Integer(), 'Must be integer', '1002'),
                    'max' => [
                        new Rule(new Integer(), 'Must be integer', '1002'),
                        new Rule(new Greater('min'), 'Max must be greater than min', '1003')
                    ],
                    'step' => new Rule(new Integer(), 'Must be integer', '1002')
                ])
            ]
        ];

        $translator = new Translator();
        $result = $translator->translate($input);
        $this->assertEquals($expectedOutput, $result);
    }
}