<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use PHPUnit\Framework\TestCase;
use Ayeo\Alligator\Tests\Sample\SampleClass;
use Ayeo\Alligator\Constraint\ClassInstance;

class ClassInstanceTest extends TestCase
{
    public function testValidObject()
    {
        $sample = new SampleClass();
        $constraint = new ClassInstance(SampleClass::class);
        $this->assertTrue($constraint->run($sample));
    }

    public function testInvalidObject()
    {
        $sample = new SampleClass();
        $constraint = new ClassInstance('UnexistingClass');
        $this->assertFalse($constraint->run($sample));
    }
}
