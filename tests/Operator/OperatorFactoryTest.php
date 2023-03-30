<?php

namespace Operator;

use Exception;
use Xudid\QueryBuilder\Operator\ComparaisonOperator;
use Xudid\QueryBuilder\Operator\LikeOperator;
use Xudid\QueryBuilder\Operator\Operator;
use Xudid\QueryBuilder\Operator\OperatorFactory;
use PHPUnit\Framework\TestCase;
use Xudid\QueryBuilder\Operator\OperatorInterface;

class OperatorFactoryTest extends TestCase
{
    public function testFactorySetPropertiesReturnFactory()
    {
        $factory = new OperatorFactory();
        $result = $factory->properties(['operator' => 'IS NULL', 'argument1' => 'a']);
        $this->assertInstanceOf(OperatorFactory::class, $result);
    }

    public function testSetPropertiesWithoutOpertatorFieldThrowException()
    {
        $factory = new OperatorFactory();
        $this->expectException(Exception::class);
        $factory->properties([]);
    }

    public function testSetPropertiesWithUnknownOperatorThrowException()
    {
        $factory = new OperatorFactory();
        $this->expectException(Exception::class);
        $factory->properties(['operator' => 'AZE']);
    }

    public function testSetPropertiesWithMissingArgumentsThrowException()
    {
        $factory = new OperatorFactory();
        $this->expectException(Exception::class);
        $factory->properties(['operator' => Operator::ISNULL]);

        $factory = new OperatorFactory();
        $this->expectException(Exception::class);
        $factory->properties(['operator' => Operator::BETWEEN]);

        $factory = new OperatorFactory();
        $this->expectException(Exception::class);
        $factory->properties(['operator' => Operator::IN]);
    }
    public function testFactoryCreateReturnOperatorInterface()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::ISNULL, 'argument1' => 'a']);
        $result = $factory->create();
        $this->assertInstanceOf(OperatorInterface::class, $result);
    }

    public function testFactoryCreateWithIsNullOPeratorPropertyReturnNullOperator()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::ISNULL, 'argument1' => 'a']);
        $result = $factory->create();
        $this->assertInstanceOf(OperatorInterface::class, $result);
    }

    public function testFactoryCreateWithEqualOPeratorPropertyReturnComparaisonOperator()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::EQUAL, 'argument1' => 'a']);
        $result = $factory->create();
        $this->assertInstanceOf(ComparaisonOperator::class, $result);
    }

    public function testFactoryCreateWithNotEqualOPeratorPropertyReturnComparaisonOperator()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::NOT_EQUAL, 'argument1' => 'a']);
        $result = $factory->create();
        $this->assertInstanceOf(ComparaisonOperator::class, $result);
    }

    public function testFactoryCreateWithLesserThanOPeratorPropertyReturnComparaisonOperator()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::LESSER_THAN, 'argument1' => 'a']);
        $result = $factory->create();
        $this->assertInstanceOf(ComparaisonOperator::class, $result);
    }

    public function testFactoryCreateWithLesserThanEqualOPeratorPropertyReturnComparaisonOperator()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::LESSER_THAN_OR_EQUAL, 'argument1' => 'a']);
        $result = $factory->create();
        $this->assertInstanceOf(ComparaisonOperator::class, $result);
    }

    public function testFactoryCreateWithGreaterThanOPeratorPropertyReturnComparaisonOperator()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::GREATER_THAN, 'argument1' => 'a']);
        $result = $factory->create();
        $this->assertInstanceOf(ComparaisonOperator::class, $result);
    }

    public function testFactoryCreateWithGreaterThanEqualOPeratorPropertyReturnComparaisonOperator()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::GREATER_THAN_OR_EQUAL, 'argument1' => 'a']);
        $result = $factory->create();
        $this->assertInstanceOf(ComparaisonOperator::class, $result);
    }

    public function testFactoryWithLikeOperatorReturnLikeOperator()
    {
        $factory = new OperatorFactory();
        $factory->properties(['operator' => Operator::LIKE, 'argument1' => 'a', 'argument2' => 'aze']);
        $result = $factory->create();
        $this->assertInstanceOf(LikeOperator::class, $result);
    }
}
