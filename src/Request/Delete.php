<?php


namespace Xudid\QueryBuilder\Request;


use Xudid\QueryBuilderContracts\Request\DeleteInterface;
use Xudid\QueryBuilderContracts\Request\RequestInterface;
use Xudid\QueryBuilder\Request\traits\HasFrom;
use Xudid\QueryBuilder\Request\traits\HasJoin;
use Xudid\QueryBuilder\Request\traits\HasWhere;

class Delete implements RequestInterface, DeleteInterface
{
    use HasFrom;
    use HasWhere;
    use HasJoin;

    private static string $requestVerb = 'DELETE';
    private array $binded = [];

    public function __construct(...$tables)
    {
        $this->from(...$tables);
    }

    public function getBindings(): array
    {
        return $this->binded;
    }

    public function toPreparedSql() : string
    {
        return self::$requestVerb .
            ' ' .
            $this->fromsToString() .
            ' ' .
            $this->joinsToString() .
            $this->wheresToString() .
            ' ' .
            ';';
    }

    public function toSql(): string
    {
        return '';
    }
}
