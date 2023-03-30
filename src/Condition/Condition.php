<?php

namespace Xudid\QueryBuilder\Condition;

use Xudid\QueryBuilder\Operator\OperatorInterface;

class Condition
{
    private string $argument1;

    private OperatorInterface $operator;
    private mixed $argument2;
    private $relation;

    public function __construct($argument1, $operator, $argument2)
    {
        $this->argument1 = $argument1;
        $this->operator = $operator;
        $this->argument2 = $argument2;
    }

    public function withRelation($relation)
    {
        $this->relation = $relation;
    }

    public function __toString(): string
    {
        $return = $this->relation .  ' ' . $this->operator;
        return $return;
    }


}