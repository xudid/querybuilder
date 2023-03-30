<?php

namespace Xudid\QueryBuilder\Operator;

class InOperator extends Operator
{
    public function __construct($argument, $range)
    {
        $this->pattern = '%param% IN(';
        $this->replacements[] = $argument;
        $valuesCount = count($range);
        $placeholderString = '';
        for ($i = 1; $i <= $valuesCount; $i++) {
            $placeholder = '%'. $argument . $i . '%';
            $placeholderString .= $placeholder . ', ';
            $this->replacements[] = ':'. $argument . $i;
        }
        $placeholderString = rtrim($placeholderString, ', ');
        $this->pattern .= $placeholderString . ')';
    }

}