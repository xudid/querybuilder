<?php

namespace Xudid\QueryBuilder\Request;

use Xudid\QueryBuilderContracts\Request\InsertInterface;
use Xudid\QueryBuilderContracts\Request\RequestInterface;

class Insert implements RequestInterface, InsertInterface
{
    private static $insertVerb = 'INSERT INTO';
    private static $valuesVerb = 'VALUES';
    private string $table;
    private array $columns = [];
    private array $values = [];
    private $binded = [];

    private int $rowCount = 0;

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
        if (!$this->columns && is_array($values)) {
            $keys = array_keys($values);
            foreach ($keys as $key) {
                if (is_numeric($key)) {
                    throw new \Exception('Can not define numeric column');
                }
            }
            $this->columns = $keys;
        }

        if (!$this->columns) {
            throw new \Exception('You need to define columns before values');

        }

        $valuesChunks = array_chunk($values, count($this->columns));
        foreach ($valuesChunks as $valuesChunk) {
            $this->rowCount ++;
            foreach ($valuesChunk as $keyIndex => $item) {
                $this->values[] = "'" . $item . "'";
                $bindingKey = $this->getBindingKey($keyIndex);
                $this->binded[$bindingKey] = $item;
            }
        }


        return $this;
    }

    public function toPreparedSql() : string
    {
        return static::$insertVerb . ' ' .
            $this->table .
            $this->columnsToString() .
            $this->valuesToString() .
            ';';
    }

    public function getBindings(): array
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
        $rowCount = $this->rowCount;
        for ($i = 1; $i <= $rowCount; $i++) {
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

    public function getBindingKey(int $key): string
    {
        $column = $this->columns[$key];
        $rowCount = $this->rowCount;
        $index = $rowCount > 0 ? $rowCount : '';
        return ':' . $column . $index;
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

    public function toSql(): string
    {
       return '';
    }
}
