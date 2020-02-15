<?php


namespace QueryBuilder;

/**
 * Class SelectRequest
 * @package QueryBuilder
 */
class SelectRequest
{
    private static string $selectVerb = 'SELECT';
    private static string $fromVerb = 'FROM';
    private static string $conditionVerb = 'WHERE';
    private static string $joinVerb = 'INNER JOIN %table2% ON %idTable1% = %idTable2%';
    private static string $distinctVerb = 'DISTINCT';
    private $selctetFields = [];
    private $froms = [];
    private $where = [];
    private $joins = [];
    private $binded = [];
    /**
     * @var bool
     */
    private bool $distinct = false;

    /**
     * SelectRequest constructor.
     * @param mixed ...$fields
     */
    public function __construct(...$fields)
    {
        if ($fields) {
            foreach ($fields as $field) {
                $this->selctetFields[] = $field;
            }
        } else {
            $this->selctetFields = '*';
        }

    }

    private function stringifySelectParams()
    {
        if(is_string($this->selctetFields)) {
            return $this->selctetFields;
        } else {
            return implode(',', $this->selctetFields);
        }
    }

    private function stringyFroms()
    {
        return self::$fromVerb . ' ' . implode(',' , $this->froms);
    }

    private function stringifyWhere() : string
    {
        $return = '';
        if (count($this->where) > 0){
            $return = self::$conditionVerb .
                ' ' .
                $this->where[0]['argument1'] .
                ' '.
                $this->where[0]['operator'] .
                ' :'.$this->where[0]['argument1'] .
                ' '
            ;

            for ($i = 1; $i < count($this->where); $i ++) {
                $return .= $this->where[$i]['relation'] .
                    ' ' .
                    $this->where[$i]['argument1'] .
                    ' '.
                    $this->where[$i]['operator'] .
                    ' :'.$this->where[$i]['argument1'];
                //echo $i;
                if ($i < (count($this->where)-3)) {
                    $return .= ' ';
                }
            }
        }
        return $return;
    }

    private function stringifyJoins()
    {
        $joinsString = '';
        foreach ($this->joins as $join) {
            $joinsString .= str_replace(
                ['%table2%', '%idTable1%', '%idTable2%'],
                [$join['table2'], $join['idTable1'], $join['idTable2']],
                self::$joinVerb
            ) . ' ';
        }
        return $joinsString;
    }

    private function stringifyDistinct() : string
    {
        if ($this->distinct) {
            return self::$distinctVerb . ' ';
        } else {
            return '';
        }
    }

    public function query() : string
    {
        $return = self::$selectVerb .
            ' ' .
            $this->stringifyDistinct() .
            $this->stringifySelectParams() .
            ' ' .
            $this->stringyFroms() .
            ' ' .
            $this->stringifyWhere() .

            $this->stringifyJoins() .
            ' ' .
            ';';
        return $return;
    }

    public function from(...$tables) : SelectRequest
    {
        foreach ($tables as $table)
        {
            $this->froms[] = $table;
        }
        return $this;
    }

    public function where(string $argument1, $operator, $argument2, $relation = 'AND') : SelectRequest
    {
        if (count($this->where) == 0) {
            $this->where[] = [
                'argument1' => $argument1,
                'operator' => $operator,
            ];
        } else {
            $this->where[] = [
                'argument1' => $argument1,
                'operator' => $operator,
                'relation' => $relation
        ];
        }
        $this->binded[$argument1] = $argument2;
        return  $this;
    }

    public function join(string $table2, string $idTable1, string $idTable2) : SelectRequest
    {
        $this->joins[] = ['table2' => $table2, 'idTable1' => $idTable1, 'idTable2' => $idTable2];
        return $this;
    }

    public function distinct() : SelectRequest
    {
        $this->distinct = true;
        return $this;
    }

    public function getBinded()
    {
        return $this->binded;
    }
}
