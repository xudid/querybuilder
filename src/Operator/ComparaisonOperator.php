<?php

namespace Xudid\QueryBuilder\Operator;

class ComparaisonOperator extends Operator
{
    public function __construct($argument, $sign)
    {
        $argumentPlaceholder = '%' . $argument . '%';
        $this->pattern = '%param% ' . $sign . ' ' . $argumentPlaceholder;
        $this->replacements= [
            $argument,
            ':' . $argument,
        ];
    }

}