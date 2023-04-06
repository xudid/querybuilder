<?php

namespace Xudid\QueryBuilder;

use Xudid\QueryBuilderContracts\QueryBuilderInterface;
use Xudid\QueryBuilderContracts\Request\DeleteInterface;
use Xudid\QueryBuilderContracts\Request\InsertInterface;
use Xudid\QueryBuilderContracts\Request\SelectInterface;
use Xudid\QueryBuilderContracts\Request\UpdateInterface;
use Xudid\QueryBuilder\Request\Delete;
use Xudid\QueryBuilder\Request\Insert;
use Xudid\QueryBuilder\Request\Select;
use Xudid\QueryBuilder\Request\Update;

/**
 * Class QueryBuilder
 * @package QueryBuilder
 */
class QueryBuilder implements QueryBuilderInterface
{
    public function select(...$fields): SelectInterface
    {
        return new Select(...$fields);
    }

    public function insert(string $table): InsertInterface
    {
        return new Insert($table);
    }

    public function update(string $table): UpdateInterface
    {
        $request = new Update($table);
        return $request;
    }

    public function delete(...$tables): DeleteInterface
    {
        $request = new Delete(...$tables);
        return $request;
    }
}
