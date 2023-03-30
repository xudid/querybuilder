<?php

namespace Xudid\QueryBuilder\Request;

class Insert
{
    private static $insertVerb = 'INSERT INTO';
    private static $valuesVerb = 'VALUES';
    private string $table;
    private array $columns = [];
    private array $values = [];
    private $binded = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function columns(...$columns): static
    {
        foreach ($columns as $column) {
            $this->columns[] = $column;
        }
        return $this;
    }

    public function values(...$values): static
    {
        foreach ($values as $value) {
            $this->values[] = "'" . $value . "'";
            $this->binded[] = $value;
        }
        return $this;
    }

    public function query()
    {
        return static::$insertVerb . ' ' .
            $this->table .
            $this->columnsToString() .
            $this->valuesToString() .
            '.';
    }

    public function getBinded()
    {
        return $this->binded;
    }

    private function valuesToString()
    {
        $values = '';
        if (count($this->values) == 0) {
            return $values;
        }

        $values = self::$valuesVerb;
        $rowCount = intdiv(count($this->values), count($this->columns));
        for($i=1; $i <= $rowCount; $i++) {
            $values .= '(';
            foreach ($this->columns as $index => $column) {
                $values .= ':' . $column . $i;
                if (array_key_last($this->columns) != $index) {
                    $values .= ', ';
                }
            }

            $values .= ')';
            if ($i <= $rowCount - 1) {
                $values .= ', ';
            }
        }

        return $values;
    }

    private function columnsToString()
    {
        $columns = '';
        if (count($this->columns) <= 0) {
            return $columns;
        }

        $columns .= '(';
        $columns .= implode(', ', $this->columns);
        $columns .= ')';
        $columns .= ' ';

        return $columns;
    }
}
