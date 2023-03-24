<?php

declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use Ayeo\Alligator\Constraint\Max;
use PHPUnit\Framework\TestCase;

class MaxTest extends TestCase
{
    /**
     * @dataProvider cases
     */
    public function testCases($isValid, $number, $value)
    {
        $constraint = new Max($number);
        $this->assertEquals($isValid, $constraint->run($value));
    }

    public function cases()
    {
        return [
            [true, 5, 4],
            [false, 4, 5],
            [false, 1000000000, 345346436346346346346346],
            [false, 1000000000, 3453464363],
            [true, 1000000000, 345346436]
        ];
    }
}