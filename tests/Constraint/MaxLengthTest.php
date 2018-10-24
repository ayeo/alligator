<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use Ayeo\Alligator\Constraint\MaxLength;
use PHPUnit\Framework\TestCase;

class MaxLengthTest extends TestCase
{
    /**
     * @dataProvider cases
     */
    public function testCases($isValid, $number, $value)
    {
        $constraint = new MaxLength($number);
        $this->assertEquals($isValid, $constraint->run($value));
    }

    public function cases()
    {
        return [
            [true, 5, null],
            [true, 0, null],
            [true, 5, ''],
            [true, 0, ''],
            [true, 5, 'test'],
            [true, 4, 'test'],
            [false, 3, 'test'],
            [false, 0, 'test'],
            [true, 5, '     '],
            [true, 5, 12345],
            [true, 5, -1234],
            [false, 4, 12345],
            [false, 4, -1234],
            [true, 4, "żółć"],
            [false, 3, "żółć"],
            [true, 2, "\n\n"],
            [false, 1, "\n\n"],
        ];
    }
}