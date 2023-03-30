<?php

namespace Xudid\QueryBuilder\Request\traits;

trait HasFrom
{
    protected static string $fromVerb = 'FROM';
    protected $froms = [];

    public function from(...$tables) : static
    {
        foreach ($tables as $table)
        {
            $this->froms[] = $table;
        }
        return $this;
    }
    protected function fromsToString()
    {
        return self::$fromVerb . ' ' . implode(',' , $this->froms);
    }
}