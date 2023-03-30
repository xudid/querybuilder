<?php

namespace Xudid\QueryBuilder\Operator;

class NullOperator extends Operator
{
    public function __construct($argument, $sign = Operator::ISNULL)
    {
        $this->pattern = '%param% ' . $sign;
        $this->replacements[] = $argument;
    }
}