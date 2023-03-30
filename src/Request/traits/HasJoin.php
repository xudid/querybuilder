<?php

namespace Xudid\QueryBuilder\Request\traits;

trait HasJoin
{
    protected static string $joinVerb = 'INNER JOIN %table2% ON %idTable1% = %idTable2%';
    protected array $joins = [];

    public function join(string $table2, string $idTable1, string $idTable2) : static
    {
        $this->joins[] = ['table2' => $table2, 'idTable1' => $idTable1, 'idTable2' => $idTable2];
        return $this;
    }
    protected function joinsToString()
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
}