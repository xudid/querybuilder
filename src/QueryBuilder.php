<?php

namespace Xudid\QueryBuilder;

use Xudid\QueryBuilder\Request\Delete;
use Xudid\QueryBuilder\Request\Insert;
use Xudid\QueryBuilder\Request\Select;
use Xudid\QueryBuilder\Request\Update;

/**
 * Class QueryBuilder
 * @package QueryBuilder
 */
class QueryBuilder
{
    public function select(...$fields): Select
    {
        return new Select($fields);
    }

    public function insert(string $table): Insert
    {
        return new Insert($table);
    }

    public function update(string $table): Update
    {
        $request = new Update($table);
        return $request;
    }

    public function delete(...$tables): Delete
    {
        $request = new Delete(...$tables);
        return $request;
    }
}

