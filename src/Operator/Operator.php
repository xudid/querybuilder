<?php

namespace Xudid\QueryBuilder\Operator;

abstract class Operator implements OperatorInterface
{
    // refactor with Enum ?
    const AND = 'AND';
    const ISNULL = 'IS NULL';
    const OR = 'OR';
    const NOT_NULL = 'IS NOT NULL';
    const BETWEEN = 'BETWEEN';
    const IN = 'IN';

    const LIKE = 'LIKE';

    // const LIKE = 'LIKE';
    // const SUBSTITUTE = '%';


    const EQUAL = '=';
    const NOT_EQUAL = '!=';

    const LESSER_THAN = '<';
    const GREATER_THAN = '>';

    const LESSER_THAN_OR_EQUAL = '<=';

    const GREATER_THAN_OR_EQUAL = '>=';
    const DIAMOND = '<>';
    public static $comparators = [self::EQUAL, self::NOT_EQUAL, self::LESSER_THAN, self::LESSER_THAN_OR_EQUAL, self::GREATER_THAN_OR_EQUAL, self::GREATER_THAN, self::DIAMOND];
    public static $nullOperators = [self::ISNULL, self::NOT_NULL];
    public  static  $operators = [self::EQUAL, self::NOT_EQUAL, self::LESSER_THAN, self::LESSER_THAN_OR_EQUAL, self::GREATER_THAN_OR_EQUAL, self::GREATER_THAN, self::DIAMOND, 'IS NULL', 'IS NOT NULL', 'BETWEEN', 'IN', self::LIKE];
    protected string $pattern = '';
    protected $binded = [];
    protected $replacements = [];

    protected string $delimiter = '%';
    public function __toString(): string
    {
        /** @lang RegExp  "#%([\w]+)%#"*/
        $placeholders = [];
        $pattern = '#('. $this->delimiter . '[\w]+' . $this->delimiter . ')#';
        preg_match_all($pattern, $this->pattern, $placeholders);
        return str_replace(
            $placeholders[1],
            $this->replacements,
            $this->pattern
        );
    }
}