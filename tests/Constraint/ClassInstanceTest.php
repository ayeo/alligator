<?php
namespace Ayeo\Validator2\Tests\Constraint;

use PHPUnit_Framework_TestCase;
use Ayeo\Validator2\Tests\Mock\SampleClass;
use Ayeo\Validator2\Constraint\ClassInstance;

class ClassInstanceTest extends PHPUnit_Framework_TestCase
{
    public function testValidObject()
    {
        $sample = new SampleClass();
        $constraint = new ClassInstance('\Ayeo\Validator\Tests\Mock\SampleClass');
        $constraint->run($sample);

        $this->assertTrue($constraint->isValid());
    }

    public function testInvalidObject()
    {
        $sample = new SampleClass();
        $constraint = new ClassInstance('UnexistingClass');
        $constraint->run($sample);

        $this->assertFalse($constraint->isValid());
    }

    /**
     * @expectedException \Ayeo\Validator2\Exception\InvalidConstraintParameter
     */
    public function testNonStringParameter()
    {
        $sample = new SampleClass();
        new ClassInstance($sample);
    }
}