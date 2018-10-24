<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use Ayeo\Alligator\Constraint\NumericMin;
use PHPUnit\Framework\TestCase;

class NumericMinTest extends TestCase
{
    public function testNoNumeric()
    {
        $constraint = new NumericMin(10);
        $this->assertFalse($constraint->run('string'));
    }

    public function testEmptyParameter()
    {
        $constraint = new NumericMin();
        $this->assertFalse($constraint->run('string'));
    }

    public function testCompareEquals()
    {
        $constraint = new NumericMin(12);
        $this->assertTrue($constraint->run(12));
    }

    public function testCompareEqualsFloat()
    {
        $constraint = new NumericMin(12.00000009);
        $this->assertFalse($constraint->run(12));
    }
}