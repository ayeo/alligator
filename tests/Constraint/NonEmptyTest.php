<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use Ayeo\Alligator\Constraint\NonEmpty;
use PHPUnit\Framework\TestCase;

class NonEmptyTest extends TestCase
{
    public function testSomething()
    {
        $constraint = new NonEmpty();
        $this->assertTrue($constraint->run('something'));
    }

    public function testNullValue()
    {
        $constraint = new NonEmpty();
        $this->assertFalse($constraint->run(null));
    }

    public function testZero()
    {
        $constraint = new NonEmpty();
        $this->assertTrue($constraint->run(0));
    }

    public function testEmptyString()
    {
        $constraint = new NonEmpty();
        $this->assertFalse($constraint->run(''));
    }

    public function testEmptyArray()
    {
        $constraint = new NonEmpty();
        $this->assertFalse($constraint->run([]));
    }

    public function testEmptyObject()
    {
        $constraint = new NonEmpty();
        $this->assertTrue($constraint->run(new \stdClass()));
    }
}
