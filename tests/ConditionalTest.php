<?php declare(strict_types=1);

namespace Ayeo\Alligator\Tests;

use Ayeo\Alligator\Alligator;
use Ayeo\Alligator\Conditional;
use Ayeo\Alligator\Constraint\Integer;
use Ayeo\Alligator\Constraint\NotNull;
use Ayeo\Alligator\Constraint\Regexp;
use Ayeo\Alligator\Rule;
use PHPUnit\Framework\TestCase;

class ConditionalTest extends TestCase
{
    public function dataProvider(): array
    {
        return [
            [21, '3542-8839', '=', true, 'Test 1'],
            [21, null, '=', false, 'Test 1a'],
            [20, null, '=', true, 'Test 2'],

            [20, null, '>', true, 'Test 3'],
            [22, null, '>', false, 'Test 3'],
        ];
    }

    /**
     * @dataProvider dataProvider()
     */
    public function testEqual($age, $number, $operator, $expectedResult, $message)
    {
        $food = new \stdClass();
        $food->age = $age;
        $food->insuranceNumber = $number;

        $recipe = [
            'age' => new Rule(new Integer(), 'must_be_numeric'),
            'insuranceNumber' => [
                new Conditional('age', $operator, '21', [
                    new Rule(new Regexp('#^[0-9]{4}-[0-9]{4}$#'), 'invalid_insurance_number'),
                    new Rule(new NotNull(), 'required')
                ])
            ]
        ];

        $alligator = new Alligator();
        $result = $alligator->taste($food, $recipe);
        $this->assertEquals($expectedResult, $result, $message);
    }
}
