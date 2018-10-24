<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests\Constraint;

use Ayeo\Alligator\Constraint\IsArray;
use PHPUnit\Framework\TestCase;

class IsArrayTest extends TestCase
{
	/**
	 * @dataProvider invalidDataProvider
	 */
    public function testInvalidData($value)
    {
        $constraint = new IsArray();
        $this->assertFalse($constraint->run($value));
    }

	/**
	 * @dataProvider validDataProvider
	 */
    public function testValidData($value)
    {
        $constraint = new IsArray();
        $this->assertTrue($constraint->run($value));
    }

	public function invalidDataProvider(): array
	{
		return [
			'string' => ['string'],
			'string2' => ['[]'],
			'int' => [12],
			'float' => [198.89],
			'boolean' => [true],
			'boolean2' => [false],
			'object' => [new \stdClass()],
			'null' => [null],
		];
    }

	public function validDataProvider(): array
	{
		return [
			[[1, 2, 3]],
			[['a', 'b']],
			[['a' => 'c', 'b' => 'd']],
		];
    }
}