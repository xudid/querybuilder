<?php

namespace QueryBuilder\Request;

use Xudid\QueryBuilder\Request\Insert;

class InsertTest extends \PHPUnit\Framework\TestCase
{
    public function testStartWellFormatted()
    {
        $request = new Insert('test');
        $query = $request->query();
        $this->assertStringStartsWith('INSERT INTO test', $query);
    }

    public function testInsertColumnsWellFormatted()
    {
        $request = new Insert('test');
        $request->columns('a', 'b', 'c');
        $query = $request->query();
        $this->assertStringStartsWith('INSERT INTO test(a, b, c)', $query);
    }

    public function testInsertColumnsValuesWellFormatted()
    {
        $request = new Insert('test');
        $request->columns('a', 'b', 'c')
            ->values(1, 2, 3);
        $query = $request->query();
        $this->assertStringStartsWith("INSERT INTO test(a, b, c) VALUES(:a1, :b1, :c1)", $query);

        $request = new Insert('test');
        $request->columns('a', 'b', 'c')
            ->values(1, 2, 3)
            ->values(4, 5, 6);
        $query = $request->query();
        $this->assertStringStartsWith("INSERT INTO test(a, b, c) VALUES(:a1, :b1, :c1), (:a2, :b2, :c2)", $query);
    }

    public function testInsertBindedValues()
    {
        $request = new Insert('test');
        $request->columns('a', 'b', 'c')
            ->values(1, 2, 3);
        $binded = $request->getBinded();
        $this->assertIsArray($binded);
        $this->assertCount(3, $binded);
    }
}