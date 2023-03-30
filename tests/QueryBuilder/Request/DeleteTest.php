<?php

namespace QueryBuilder\Request;

use Xudid\QueryBuilder\Operator\Operator;
use Xudid\QueryBuilder\Request\Delete;

class DeleteTest extends \PHPUnit\Framework\TestCase
{
    public function testQueryReturnAString()
    {
        $request = new Delete;
        $result = $request->query();
        $this->assertIsString($result);
    }

    public function testDeleteStartWellFormatted()
    {
        $request = new Delete('test');
        $result = $request->query();
        $this->assertStringStartsWith('DELETE FROM test', $result);
    }

    public function testDeleteWhereWellFormatted()
    {
        $request = new Delete('test');
        $request->where('a', Operator::EQUAL, '2');
        $result = $request->query();
        $this->assertStringStartsWith('DELETE FROM test WHERE a = :a', $result);
    }

    public function testDeleteJoinWhereWellFormatted()
    {
        $request = new Delete('test');
        $request->join('test2', 'test.id', 'test2.id')
            ->where('a', Operator::EQUAL, '2');
        $result = $request->query();
        $this->assertStringStartsWith('DELETE FROM test INNER JOIN test2 ON test.id = test2.id WHERE a = :a', $result);
    }
}
