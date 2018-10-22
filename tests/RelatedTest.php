<?php declare(strict_types=1);

namespace Ayeo\Validator2\Tests;

use Ayeo\Validator2\Constraint\Greater;
use Ayeo\Validator2\Error;
use Ayeo\Validator2\Rule;
use Ayeo\Validator2\Tests\Mock\Range;
use Ayeo\Validator2\Validator;
use PHPUnit\Framework\TestCase;

class RelatedTest extends TestCase
{
    public function testTest()
    {
        $range = new Range();
        $range->min = 10;
        $range->max = 5;

        $rules = [
            'max' => new Rule(new Greater('min'), 'Max must be greater than min')
        ];

        $validator = new Validator($rules);
        $validator->validate($range);
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
            'max' => new Rule(new Greater('min'), 'Max must be greater than min')
        ];

        $validator = new Validator($rules);
        $this->assertTrue($validator->validate($range));
    }

    public function testWithUnsetMax()
    {
        $range = new Range();
        $range->min = 10;

        $rules = [
            'max' => new Rule(new Greater('min'), 'Max must be greater than min')
        ];

        $validator = new Validator($rules);
        $this->assertTrue($validator->validate($range));
    }
}