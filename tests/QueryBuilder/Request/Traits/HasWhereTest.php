<?php

namespace QueryBuilder\Request\Traits;

use Xudid\QueryBuilder\Request\Select;
use PHPUnit\Framework\TestCase;

class HasWhereTest extends TestCase
{
    public function testSelectWhereReturnSelectInstance()
    {
        $request = new Select('a', 'b');
        $result = $request->from('test')
            ->where('a', '=', 1)
            ->where('b', '=', 1);
        $this->assertInstanceOf(Select::class, $result);
    }

    public function testSelectWhereReturnSelectInstanceBindFields()
    {
        $request = new Select('a', 'b');
        $request->from('test')
            ->where('a', '=', 1)
            ->where('b', '=', 2);
        $binded = $request->getBinded();
        $this->assertIsArray($binded);
        $this->assertCount(2, $binded);
        $this->assertArrayHasKey('a', $binded);
        $this->assertArrayHasKey('b', $binded);
        $this->assertEquals('1', $binded['a']);
        $this->assertEquals('2', $binded['b']);
    }
}
