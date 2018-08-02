<?php

namespace Railken\LaraEye\Tests;

use Railken\LaraEye\Filter;
use Railken\SQ\Exceptions\QuerySyntaxException;

class FilterTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__.'/..', '.env');
        $dotenv->load();
        parent::setUp();
    }

    /**
     * Retrieve a new instance of query.
     *
     * @param string $str_filter
     * @param array  $keys
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function newQuery($str_filter, $keys = ['id', 'x', 'y', 'z', 'created_at'])
    {
        $filter = new Filter('foo', $keys);
        $query = (new Foo())->newQuery()->getQuery();
        $filter->build($query, $str_filter);

        return $query;
    }

    public function testFilterUndefindKey()
    {
        $this->expectException(QuerySyntaxException::class);
        $this->newQuery('d eq 1');
    }

    public function testFilterAndWrong()
    {
        $this->expectException(QuerySyntaxException::class);
        $this->newQuery('x and 1');
    }

    public function testFilterConcatFunction()
    {
        $this->assertEquals('select * from `foo` where `x` = CONCAT(`x`,2)', $this->newQuery('x eq concat(x,2)')->toSql());
    }

    public function testFilterAllKeysValid()
    {
        $this->assertEquals('select * from `foo` where `d` = `f`', $this->newQuery('d eq f', ['*'])->toSql());
    }

    public function testFilterEqColumns()
    {
        $this->assertEquals('select * from `foo` where `x` = `x`', $this->newQuery('x eq x')->toSql());
        $this->assertEquals('select * from `foo` where `x` = `x`', $this->newQuery('x = x')->toSql());
    }

    public function testFilterEq()
    {
        $this->assertEquals('select * from `foo` where `x` = ?', $this->newQuery('x eq 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` = ?', $this->newQuery('x = 1')->toSql());
    }

    public function testFilterGt()
    {
        $this->assertEquals('select * from `foo` where `x` > ?', $this->newQuery('x gt 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` > ?', $this->newQuery('x > 1')->toSql());
    }

    public function testFilterGte()
    {
        $this->assertEquals('select * from `foo` where `x` >= ?', $this->newQuery('x gte 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` >= ?', $this->newQuery('x >= 1')->toSql());
    }

    public function testFilterLt()
    {
        $this->assertEquals('select * from `foo` where `x` < ?', $this->newQuery('x lt 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` < ?', $this->newQuery('x < 1')->toSql());
    }

    public function testFilterLte()
    {
        $this->assertEquals('select * from `foo` where `x` <= ?', $this->newQuery('x lte 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` <= ?', $this->newQuery('x <= 1')->toSql());
    }

    public function testFilterCt()
    {
        $this->assertEquals('select * from `foo` where `x` like ?', $this->newQuery('x ct 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` like ?', $this->newQuery('x *= 1')->toSql());
    }

    public function testFilterSw()
    {
        $this->assertEquals('select * from `foo` where `x` like ?', $this->newQuery('x sw 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` like ?', $this->newQuery('x ^= 1')->toSql());
    }

    public function testFilterEw()
    {
        $this->assertEquals('select * from `foo` where `x` like ?', $this->newQuery('x ew 1')->toSql());
        $this->assertEquals('select * from `foo` where `x` like ?', $this->newQuery('x $= 1')->toSql());
    }

    public function testFilterIn()
    {
        $this->assertEquals('select * from `foo` where `x` in (?)', $this->newQuery('x in (1)')->toSql());
        $this->assertEquals('select * from `foo` where `x` in (?)', $this->newQuery('x =[] (1)')->toSql());
    }

    public function testFilterNotIn()
    {
        $this->assertEquals('select * from `foo` where `x` not in (?)', $this->newQuery('x not in (1)')->toSql());
        $this->assertEquals('select * from `foo` where `x` not in (?)', $this->newQuery('x !=[] (1)')->toSql());
    }

    public function testFilterAnd()
    {
        $this->assertEquals('select * from `foo` where (`x` = ? and `x` = ?)', $this->newQuery('x = 1 and x = 2')->toSql());
        $this->assertEquals('select * from `foo` where (`x` = ? and `x` = ?)', $this->newQuery('x = 1 && x = 2')->toSql());
    }

    public function testFilterOr()
    {
        $this->assertEquals('select * from `foo` where (`x` = ? or `x` = ?)', $this->newQuery('x = 1 or x = 2')->toSql());
        $this->assertEquals('select * from `foo` where (`x` = ? or `x` = ?)', $this->newQuery('x = 1 || x = 2')->toSql());
    }

    public function testFilterNull()
    {
        $this->assertEquals('select * from `foo` where `x` is null', $this->newQuery('x is null')->toSql());
    }

    public function testFilterNotNull()
    {
        $this->assertEquals('select * from `foo` where `x` is not null', $this->newQuery('x is not null')->toSql());
    }

    public function testGrouping()
    {
        $this->assertEquals('select * from `foo` where (`x` = ? or (`x` = ? and `x` = ?))', $this->newQuery('x = 1 or (x = 2 and x = 3)')->toSql());
        $this->assertEquals('select * from `foo` where (`x` = ? and (`x` = ? or `x` = ?))', $this->newQuery('x = 1 and (x = 2 or x = 3)')->toSql());
        $this->assertEquals('select * from `foo` where (`x` = ? and (`x` = ?))', $this->newQuery('x = 1 and (x = 2)')->toSql());
    }
}
