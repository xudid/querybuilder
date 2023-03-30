<?php


namespace Xudid\QueryBuilder\Request;


use Xudid\QueryBuilder\Request\traits\HasFrom;
use Xudid\QueryBuilder\Request\traits\HasJoin;
use Xudid\QueryBuilder\Request\traits\HasWhere;

class Delete
{
    use HasFrom;
    use HasWhere;
    use HasJoin;

    const TYPE = 'DELETE';

    private static string $requestVerb = 'DELETE';
    private array $binded = [];

    public function __construct(...$tables)
    {
        $this->from(...$tables);
    }

    public function getBinded()
    {
        return $this->binded;
    }

    public function query() : string
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
}
