<?php

namespace Xudid\QueryBuilder\Request;

use Xudid\QueryBuilderContracts\Request\RequestInterface;
use Xudid\QueryBuilderContracts\Request\UpdateInterface;
use Xudid\QueryBuilder\Request\traits\HasJoin;
use Xudid\QueryBuilder\Request\traits\HasWhere;

class Update implements RequestInterface, UpdateInterface
{
    use HasWhere;
    use HasJoin;

    private static string $updatePattern = 'UPDATE %table% ';
    private string $table;
    private array $sets = [];
    private array $binded = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function set(string $column, $value) : static
    {
        $this->sets[$column] =  $value;
        $this->binded[$column] = $value;
        return $this;
    }

    public function toPreparedSql() : string
    {
        $query = str_replace('%table%', $this->table, self::$updatePattern);
        $query .=$this->joinsToString();
        $query .= $this->setsToString();
        $query .= $this->wheresToString();

        return $query;
    }

    public function getBindings(): array
    {
        return $this->binded;
    }

    private function setsToString() : string
    {
        $sets = 'SET ';
        foreach ($this->sets as $column => $value) {
            $sets .= $column . ' = ' . ':'. $column;
            if (array_key_last($this->sets) != $column) {
                $sets .= ', ';
            } else {
                $sets .= ' ';
            }
        }
        return $sets;
    }

    public function toSql(): string
    {
       return '';
    }
}
