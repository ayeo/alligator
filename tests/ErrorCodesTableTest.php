<?php declare(strict_types = 1);

namespace Ayeo\Validator2\Tests;

use Ayeo\Validator2\Constraint\MinLength;
use Ayeo\Validator2\Error;
use Ayeo\Validator2\ErrorCodesTable;
use Ayeo\Validator2\Rule;
use Ayeo\Validator2\Tests\Mock\SampleClass;
use Ayeo\Validator2\Validator;
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

        $validator = new Validator();
        $validator->validate($object, $rules);
        $errors = $validator->getErrors($table);

        $expected = [
            'name' => new Error('1882', ['minLength' => 20], 'Name is to short')
        ];
        $this->assertEquals($expected, $errors);
    }

    //todo: test nested
}