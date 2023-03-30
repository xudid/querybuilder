<?php

namespace QueryBuilder\Request;

use Exception;
use Xudid\QueryBuilder\Operator\Operator;
use PHPUnit\Framework\TestCase;
use Xudid\QueryBuilder\Request\Select;

class SelectTest extends TestCase
{

    public function testQueryReturnAString()
    {
        $request = new Select;
        $result = $request->query();
        $this->assertIsString($result);
    }

    public function testQueryFromWithoutFiledsWellFormatted()
    {
        $request = new Select;
        $request->from('test');
        $result = $request->query();
        $this->assertStringStartsWith('SELECT * FROM test', $result);
    }

    public function testQueryFromWithFieldsWellFormated()
    {
        $request = new Select('a', 'b');
        $request->from('test');
        $result = $request->query();
        $this->assertStringStartsWith('SELECT a, b FROM test', $result);
    }

    public function testQueryFromWhereWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')->where('a', '=', 1);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a = :a", $result);
    }

    public function testQueryFromAndWhereWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', '=', 1)
            ->where('b', '=', 1);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a = :a AND b = :b", $result);
    }

    public function testQueryFromWhereOrWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', '=', 1)
            ->where('b', '=', 1, Select::OR);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a = :a OR b = :b", $result);
    }

    public function testQueryFromWhereAndOrWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::EQUAL, 1)
            ->where('b', Operator::EQUAL, 1, Select::AND)
            ->where('c', Operator::EQUAL, 1, Select::OR);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a = :a AND b = :b OR c = :c", $result);
    }

    public function testQueryFromWhereNullFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::ISNULL);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a IS NULL", $result);
    }

    public function testQueryFromWhereNullAndFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::ISNULL)
            ->where('b', '=', 1, Select::AND);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a IS NULL AND b = :b", $result);
    }

    public function testQueryFromWhereIsNotNullAndFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::NOT_NULL)
            ->where('b', '=', 1, Select::AND);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a IS NOT NULL AND b = :b", $result);
    }

    public function testQueryFromWhereIsBetweenAndWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::BETWEEN, [1, 10])
            ->where('b', '=', 1, Select::AND);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a BETWEEN :a1 AND :a2 AND b = :b", $result);
    }

    public function testQueryFromWhereInWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::IN, [1, 10])
            ->where('b', '=', 1, Operator::AND);
        $result = $request->query();
        $this->assertStringStartsWith("SELECT a, b FROM test WHERE a IN(:a1, :a2) AND b = :b", $result);
    }

    public function testQueryFromWhereGroupByWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::IN, [1, 10])
            ->where('b', '=', 1, Operator::AND)
            ->groupBy('a');
        $result = $request->query();
        $this->assertStringStartsWith(
            "SELECT a, b FROM test WHERE a IN(:a1, :a2) AND b = :b GROUP BY a",
            $result
        );
    }

    public function testQueryFromWhereGroupByHavingWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::IN, [1, 10])
            ->where('b', '=', 1, Operator::AND)
            ->groupBy('a')
            ->having('a > 3');
        $result = $request->query();
        $this->assertStringStartsWith(
            "SELECT a, b FROM test WHERE a IN(:a1, :a2) AND b = :b GROUP BY a HAVING a > 3",
            $result
        );
    }

    public function testQuesryFromWhereOrderByWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::IN, [1, 10])
            ->where('b', '=', 1, Operator::AND)
            ->orderBy('a');
        $result = $request->query();
        $this->assertStringStartsWith(
            "SELECT a, b FROM test WHERE a IN(:a1, :a2) AND b = :b ORDER BY a ASC",
            $result
        );

        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::IN, [1, 10])
            ->where('b', '=', 1, Operator::AND)
            ->orderBy('a', 'ASC')
            ->orderBy('b', 'ASC');
        $result = $request->query();
        $this->assertStringStartsWith(
            "SELECT a, b FROM test WHERE a IN(:a1, :a2) AND b = :b ORDER BY a, b ASC",
            $result
        );

        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', Operator::IN, [1, 10])
            ->where('b', '=', 1, Operator::AND)
            ->orderBy('a')
            ->orderBy('b', 'DESC')
            ->orderBy('c', 'ASC');
        $result = $request->query();
        $this->assertStringStartsWith(
            "SELECT a, b FROM test WHERE a IN(:a1, :a2) AND b = :b ORDER BY a ASC, b DESC, c ASC",
            $result
        );
    }

    public function testQueryWhereOrderByWithUnknownDirectionThrowException()
    {
        $request = new Select('a', 'b');
        $this->expectException(Exception::class);
        $request->from('test')
            ->where('b', '=', 1, Operator::AND)
            ->orderBy('b', 'AST');
    }

    public function testQueryWhereLimitWellFormatted()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('b', '=', 1)
            ->limit(10);
        $result = $request->query();
        $this->assertStringStartsWith(
            "SELECT a, b FROM test WHERE b = :b LIMIT 10",
            $result
        );
    }
}
