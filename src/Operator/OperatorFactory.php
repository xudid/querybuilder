<?php

namespace Xudid\QueryBuilder\Operator;

use Exception;

class OperatorFactory
{
    private ?OperatorInterface $operator = null;

    public function create(): OperatorInterface
    {
        if (! $this->operator) {
            throw new Exception('Factory fatal error');
        }
        return $this->operator;
    }

    public function properties(array $properties)
    {
        if (!array_key_exists('operator', $properties)) {
            throw new Exception('Missing property operator');
        }

        if (!in_array($properties['operator'], Operator::$operators)) {
            throw new Exception('Unknown operator');
        }

        if (
            in_array($properties['operator'], Operator::$comparators)

        ) {
            if (!array_key_exists('argument1', $properties)) {
                throw new Exception('Missing argument');
            }
            $comparator = $properties['operator'];
            $this->operator = new ComparaisonOperator($properties['argument1'], $comparator);
            return $this;
        }

        if (in_array($properties['operator'], Operator::$nullOperators)) {
            if (!array_key_exists('argument1', $properties)) {
                throw new Exception('Missing argument');
            }
            $verb = $properties['operator'];
            $this->operator = new NullOperator($properties['argument1'], $verb);
            return $this;
        }

        if (!array_key_exists('argument1', $properties) || !array_key_exists('argument2', $properties)) {
            throw new Exception('Missing argument');
        }
        switch ($properties['operator']) {
            case Operator::BETWEEN :
                $this->operator = new BetweenOperator($properties['argument1'], $properties['argument2']);
            break;
            case Operator::IN :
                $this->operator = new InOperator($properties['argument1'], $properties['argument2']);
            break;
            case Operator::LIKE :
                $this->operator = new LikeOperator($properties['argument1'], $properties['argument2']);
            break;
        }

        return $this;
    }
}