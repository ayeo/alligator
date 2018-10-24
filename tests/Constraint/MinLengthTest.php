<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use Ayeo\Alligator\Constraint\MinLength;
use PHPUnit\Framework\TestCase;

class MinLengthTest extends TestCase
{
    /**
     * @dataProvider cases
     */
    public function testCases(bool $isValid, int $number, ?string $value)
    {
        $constraint = new MinLength($number);
        $this->assertEquals($isValid, $constraint->run($value));
    }

    public function cases()
    {
        return [
            [false, 5, null],
            [true, 0, null],
            [false, 5, ''],
            [true, 0, ''],
            [false, 5, 'test'],
            [true, 4, 'test'],
            [true, 3, 'test'],
            [true, 0, 'test'],
            [true, 5, '     '],
            [true, 5, 12345],
            [true, 5, -1234],
            [false, 6, 12345],
            [false, 6, -1234],
            [true, 4, "żółć"],
            [false, 5, "żółć"],
            [true, 2, "\n\n"],
            [false, 3, "\n\n"],
        ];
    }
}