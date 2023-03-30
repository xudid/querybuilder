<?php

namespace Xudid\QueryBuilder\Operator;

class LikeOperator extends Operator
{


    public function __construct(string $param, string $argument)
    {
        $this->delimiter = '£';
        $this->pattern = '£param£ LIKE £argument£';
        $this->replacements[] = $param;
        $this->replacements[] = ':' . $param;
        $this->binded[$param] = $argument;
    }
}