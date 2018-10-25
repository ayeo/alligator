<?php declare(strict_types = 1);

namespace Ayeo\Alligator\Tests;

use Ayeo\Alligator\Constraint\MinLength;
use Ayeo\Alligator\Error;
use Ayeo\Alligator\ErrorCodesTable;
use Ayeo\Alligator\Rule;
use Ayeo\Alligator\Tests\Sample\SampleClass;
use Ayeo\Alligator\Alligator;
use PHPUnit\Framework\TestCase;

class ErrorCodesTableTest extends TestCase
{
    public function testTest(): void
    {
        $rules = ['name' => new Rule(new MinLength(20), '1882')];

        $object = new SampleClass();
        $object->name = 'Sample name';

        $table = new ErrorCodesTable();
        $table->add('1882', 'Name is to short');

        $validator = new Alligator();
        $validator->taste($object, $rules);
        $errors = $validator->getErrors($table);

        $expected = [
            'name' => new Error('1882', ['minLength' => 20], 'Name is to short')
        ];
        $this->assertEquals($expected, $errors);
    }

    //todo: test nested
}