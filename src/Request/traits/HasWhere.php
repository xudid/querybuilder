<?php

namespace Xudid\QueryBuilder\Request\traits;

use Xudid\QueryBuilder\Condition\Condition;
use Xudid\QueryBuilder\Operator\Operator;
use Xudid\QueryBuilder\Operator\OperatorFactory;

trait HasWhere
{
    const CONDITION_VERB = 'WHERE';
    const AND = 'AND';
    const ISNULL = 'IS NULL';
    const OR = 'OR';
    const NOT_NULL = 'IS NOT NULL';
    const BETWEEN = 'BETWEEN';
    const IN = 'IN(%in_values_n%)';
    protected array $wheres = [];

    public function where(string $argument1, $operatorType, $argument2 = '', $relation = 'AND'): static
    {
        $operatorFactory = new OperatorFactory();
        $operatorFactory->properties([
            'operator' => $operatorType,
            'argument1' => $argument1,
            'argument2' => $argument2
        ]);
        $operator = $operatorFactory->create();
        $condition = new Condition($argument1, $operator, $argument2);
        if (count($this->wheres) == 0) {
            $this->wheres[] = $condition;
        } else {
            $condition->withRelation($relation);
            $this->wheres[] = $condition;
        }
        if ($operatorType == Operator::ISNULL) {
            return $this;
        }

        $this->binded[$argument1] = $argument2;
        return $this;
    }

    protected function wheresToString(): string
    {
        if (!$this->wheres) {

            return '';
        }

        $return = self::CONDITION_VERB;

        foreach ($this->wheres as $index => $condition) {
            $return .= $condition;
            if ($index >= 0 && count($this->wheres) > 1) {
                $return .= ' ';
            }
        }

        return $return;
    }
}
