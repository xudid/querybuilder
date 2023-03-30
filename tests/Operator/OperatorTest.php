<?php

namespace Operator;

use Xudid\QueryBuilder\Operator\BetweenOperator;
use Xudid\QueryBuilder\Operator\ComparaisonOperator;
use Xudid\QueryBuilder\Operator\InOperator;
use Xudid\QueryBuilder\Operator\NullOperator;
use PHPUnit\Framework\TestCase;
use Xudid\QueryBuilder\Operator\Operator;
use Xudid\QueryBuilder\Operator\LikeOperator;

class OperatorTest extends TestCase
{
    public function testISNULL()
    {
        $operator = new NullOperator('a');
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a IS NULL', $result);
    }

    public function testISNotNULL()
    {
        $operator = new NullOperator('a', Operator::NOT_NULL);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a IS NOT NULL', $result);
    }

    public function testBetween()
    {
        $operator = new BetweenOperator('a', ['1', '2']);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a BETWEEN :a1 AND :a2', $result);

        $operator = new BetweenOperator('a', ['3', '4']);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a BETWEEN :a1 AND :a2', $result);
    }

    public function testIn()
    {
        $operator = new InOperator('a', ['1', '2']);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a IN(:a1, :a2)', $result);

        $operator = new InOperator('a', ['3', '4', '5']);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a IN(:a1, :a2, :a3)', $result);
    }

    public function testEq()
    {
        $operator = new ComparaisonOperator('a', Operator::EQUAL);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a = :a', $result);
    }

    public function testNEQ()
    {
        $operator = new ComparaisonOperator('a', Operator::NOT_EQUAL);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a != :a', $result);

        $operator = new ComparaisonOperator('a', Operator::DIAMOND);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a <> :a', $result);
    }

    public function testLT()
    {
        $operator = new ComparaisonOperator('a', Operator::LESSER_THAN);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a < :a', $result);
    }

    public function testLTE()
    {
        $operator = new ComparaisonOperator('a', Operator::LESSER_THAN_OR_EQUAL);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a <= :a', $result);
    }

    public function testGT()
    {
        $operator = new ComparaisonOperator('a', Operator::GREATER_THAN);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a > :a', $result);
    }

    public function testGTE()
    {
        $operator = new ComparaisonOperator('a', Operator::GREATER_THAN_OR_EQUAL);
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a >= :a', $result);
    }

    public function testLike()
    {
        $operator = new LikeOperator('a', 'aze');
        $result = (string) $operator;
        $this->assertIsString($result);
        $this->assertEquals('a LIKE :a', $result);
    }
}
