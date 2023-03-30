<?php

namespace Xudid\QueryBuilder\Operator;

class BetweenOperator extends Operator
{
    public function __construct($argument, $range)
    {
        $this->pattern = '%param% BETWEEN %start_range% AND %end_range%';
        $this->replacements[] = $argument;
        $this->replacements[] = ':' . $argument . '1';
        $this->replacements[] = ':' . $argument . '2';
    }
}