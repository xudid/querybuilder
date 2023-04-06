<?php

namespace QueryBuilder\Request;

use PHPUnit\Framework\TestCase;
use Xudid\QueryBuilder\Operator\Operator;
use Xudid\QueryBuilder\Request\Update;

class UpdateTest extends TestCase
{
    public function testQueryReturnAString()
    {
        $request = new Update('test');
        $result = $request->toPreparedSql();
        $this->assertIsString($result);
    }

    public function testUpdateStartWellFormatted()
    {
        $request = new Update('test');
        $result = $request->toPreparedSql();
        $this->assertStringStartsWith('UPDATE test SET', $result);
    }

    public function testUpdateWithOneFieldWellFormatted()
    {
        $request = new Update('test');
        $request->set('a', 1);
        $result = $request->toPreparedSql();
        $this->assertStringStartsWith("UPDATE test SET a = :a", $result);
    }

    public function testUpdateWithSeveralFieldsWellFormatted()
    {
        $request = new Update('test');
        $request->set('a', 1)->set('b', 2);
        $result = $request->toPreparedSql();
        $this->assertStringStartsWith("UPDATE test SET a = :a, b = :b", $result);
    }

    public function testUpdateWithOneFieldAndWhereWellFormatted()
    {
        $request = new Update('test');
        $request->set('a', 1)->where('b', Operator::EQUAL, 2);
        $result = $request->toPreparedSql();
        $this->assertStringStartsWith("UPDATE test SET a = :a WHERE b = :b", $result);
    }

    public function testUpdateWithJoinAndWhere()
    {
        $request = new Update('test');
        $request->set('a', 1)
            ->join('test2', 'test.id', 'test2.id')
            ->where('b', Operator::EQUAL, 2);
        $result = $request->toPreparedSql();
        $this->assertStringStartsWith("UPDATE test INNER JOIN test2 ON test.id = test2.id SET a = :a WHERE b = :b", $result);
    }
}
