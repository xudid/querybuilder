<?php


namespace Xudid\QueryBuilder\Request;

use Exception;
use Xudid\QueryBuilderContracts\Request\RequestInterface;
use Xudid\QueryBuilderContracts\Request\SelectInterface;
use Xudid\QueryBuilder\Request\traits\HasFrom;
use Xudid\QueryBuilder\Request\traits\HasJoin;
use Xudid\QueryBuilder\Request\traits\HasWhere;

/**
 * Class Select
 * @package QueryBuilder
 */
class Select implements RequestInterface, SelectInterface
{
    use HasFrom;
    use HasWhere;
    use HasJoin;
    private static string $selectVerb = 'SELECT';
    private static string $distinctVerb = 'DISTINCT';
    private static string $groupByVerb = 'GROUP BY';
    private static string $havingVerb = 'HAVING';
    private static string $orderByVerb = 'ORDER BY';
    private $selctetFields = [];
    private array $binded = [];
    private bool $distinct = false;
    private string $groupBy = '';
    private string $having = '';
    private array $orderBys = [];
    private int $limit = 0;

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

    public function distinct() : static
    {
        $this->distinct = true;
        return $this;
    }

    public function groupBy(string $fieldName): static
    {
        $this->groupBy = $fieldName;
        return $this;
    }

    public function having(string $havingCondition): static
    {
        $this->having = $havingCondition;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function orderBy($field, $direction = 'ASC'): static
    {
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new Exception('Order By Exception invalid direction');
        }
        $this->orderBys[$field] = $direction;
        return $this;
    }

    public function limit($limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function getBindings(): array
    {
        return $this->binded;
    }

    public function toPreparedSql() : string
    {
        $query = self::$selectVerb . ' ';
        $query .= $this->distinctToString();
        $query .= $this->selectParamsToString() . ' ';
        $query .= $this->fromsToString() . ' ';
        $query .= $this->joinsToString();
        $query .= $this->wheresToString();
        $query .= $this->groupByToString();
        $query .= $this->havingToString();
        $query .= $this->orderByToString();
        $query .= $this->limitToString(); //"with offset"
        $query .= ';';
        return $query;
    }

    private function distinctToString() : string
    {
        if (!$this->distinct) {
            return '';
        }

        return self::$distinctVerb . ' ';
    }

    private function selectParamsToString()
    {
        if(is_string($this->selctetFields)) {
            return $this->selctetFields;
        } else {
            return implode(', ', $this->selctetFields);
        }
    }

    private function groupByToString()
    {
        if (!$this->groupBy) {
            return '';
        }

        return static::$groupByVerb . ' ' . $this->groupBy . ' ';
    }

    private function havingToString()
    {
        if (!$this->having) {
            return '';
        }

        return static::$havingVerb . ' ' . $this->having . ' ';
    }

    private function orderByToString()
    {
        if (!$this->orderBys) {
            return '';
        }
        $directions = array_unique(array_values($this->orderBys));
        if (count($directions) == 1) {
            $direction = $directions[0];
            $columns = array_keys($this->orderBys);
            $columns = implode(', ', $columns);

            return static::$orderByVerb . ' ' . $columns . ' ' . $direction . ' ';
        } else {
            $return = static::$orderByVerb . ' ';
            foreach ($this->orderBys as $field => $direction) {
                $return .= $field . ' ' . $direction . ', ';
            }
            $return = rtrim($return, ', ');

            return $return;
        }
    }

    private function limitToString()
    {
        if (!$this->limit) {
            return '';
        }

        return ' LIMIT ' . $this->limit;
    }

    public function toSql(): string
    {
       return '';
    }
}
