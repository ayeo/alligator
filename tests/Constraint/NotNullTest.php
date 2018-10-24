<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use Ayeo\Alligator\Constraint\NotNull;
use PHPUnit\Framework\TestCase;

class NotNullTest extends TestCase
{
    public function testNullValue()
    {
        $constraint = new NotNull();
        $this->assertFalse($constraint->run(null));
    }

    public function testZero()
    {
        $constraint = new NotNull();
        $this->assertTrue($constraint->run(0));
    }

    public function testEmptyString()
    {
        $constraint = new NotNull();
        $this->assertTrue($constraint->run(''));
    }
}