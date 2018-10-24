<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use Ayeo\Alligator\Constraint\InArray;
use PHPUnit\Framework\TestCase;

class InArrayTest extends TestCase
{
    public function testBase()
    {
        $constraint = new InArray([1, 2, 3]);
        $this->assertFalse($constraint->run(4));
    }

    public function testNull()
    {
        $constraint = new InArray([1, 2, 3]);
        $this->assertFalse($constraint->run(null));
    }
}
