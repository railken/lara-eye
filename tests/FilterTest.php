<?php

namespace Railken\LaraEye\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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

        Schema::dropIfExists('foo');

        Schema::create('foo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('x')->nullable();
            $table->string('y')->nullable();
            $table->string('z')->nullable();
            $table->string('d')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Retrieve a new instance of query.
     *
     * @param string $str_filter
     * @param array  $keys
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function newQuery($str_filter, $keys)
    {
        $filter = new Filter('foo', $keys);
        $query = (new Foo())->newQuery()->getQuery();
        $filter->build($query, $str_filter);

        return $query;
    }

    public function testFilterUndefindKey()
    {
        $this->expectException(QuerySyntaxException::class);
        $this->newQuery('d eq 1', ['x']);
    }

    public function assertQuery(string $sql, string $filter, $keys = ['id', 'x', 'y', 'z', 'created_at'])
    {
        $query = $this->newQuery($filter, $keys);
        $this->assertEquals($sql, $query->toSql());
        $query->get();
    }

    public function testFilterAndWrong()
    {
        $this->expectException(QuerySyntaxException::class);
        $this->newQuery('x and 1', ['*']);
    }

    public function testFilterConcatFunction()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` = CONCAT(`foo`.`x`,?)', 'x eq concat(x,2)');
        $this->assertQuery('select * from `foo` where `foo`.`x` = CONCAT(`foo`.`x`,CONCAT(`foo`.`y`,?))', 'x eq concat(x,concat(y,3))');
    }

    public function testFilterDateFormatFunction()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` = DATE_FORMAT(`foo`.`x`,?)', 'x eq date_format(x,"%d")');
    }

    /*public function testFilterSumFunction()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` = SUM(`foo`.`x`)', 'x eq sum(x)');
    }*/

    public function testFilterAllKeysValid()
    {
        $this->assertQuery('select * from `foo` where `foo`.`d` = `foo`.`x`', 'd eq x', ['*']);
    }

    public function testFilterEqColumns()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` = `foo`.`x`', 'x eq x');
        $this->assertQuery('select * from `foo` where `foo`.`x` = `foo`.`x`', 'x = x');
    }

    public function testFilterEq()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` = ?', 'x eq 1');
        $this->assertQuery('select * from `foo` where `foo`.`x` = ?', 'x = 1');
    }

    public function testFilterGt()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` > ?', 'x gt 1');
        $this->assertQuery('select * from `foo` where `foo`.`x` > ?', 'x > 1');
    }

    public function testFilterGte()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` >= ?', 'x gte 1');
        $this->assertQuery('select * from `foo` where `foo`.`x` >= ?', 'x >= 1');
    }

    public function testFilterLt()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` < ?', 'x lt 1');
        $this->assertQuery('select * from `foo` where `foo`.`x` < ?', 'x < 1');
    }

    public function testFilterLte()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` <= ?', 'x lte 1');
        $this->assertQuery('select * from `foo` where `foo`.`x` <= ?', 'x <= 1');
    }

    public function testFilterCt()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` like ?', 'x ct 1');
        $this->assertQuery('select * from `foo` where `foo`.`x` like ?', 'x *= 1');
    }

    public function testFilterSw()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` like ?', 'x sw 1');
        $this->assertQuery('select * from `foo` where `foo`.`x` like ?', 'x ^= 1');
    }

    public function testFilterEw()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` like ?', 'x ew 1');
        $this->assertQuery('select * from `foo` where `foo`.`x` like ?', 'x $= 1');
    }

    public function testFilterIn()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` in (?)', 'x in (1)');
        $this->assertQuery('select * from `foo` where `foo`.`x` in (?)', 'x =[] (1)');
    }

    public function testFilterNotIn()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` not in (?)', 'x not in (1)');
        $this->assertQuery('select * from `foo` where `foo`.`x` not in (?)', 'x !=[] (1)');
    }

    public function testFilterAnd()
    {
        $this->assertQuery('select * from `foo` where (`foo`.`x` = ? and `foo`.`x` = ?)', 'x = 1 and x = 2');
        $this->assertQuery('select * from `foo` where (`foo`.`x` = ? and `foo`.`x` = ?)', 'x = 1 && x = 2');
    }

    public function testFilterOr()
    {
        $this->assertQuery('select * from `foo` where (`foo`.`x` = ? or `foo`.`x` = ?)', 'x = 1 or x = 2');
        $this->assertQuery('select * from `foo` where (`foo`.`x` = ? or `foo`.`x` = ?)', 'x = 1 || x = 2');
    }

    public function testFilterNull()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` is null', 'x is null');
    }

    public function testFilterNotNull()
    {
        $this->assertQuery('select * from `foo` where `foo`.`x` is not null', 'x is not null');
    }

    public function testGrouping()
    {
        $this->assertQuery('select * from `foo` where (`foo`.`x` = ? or (`foo`.`x` = ? and `foo`.`x` = ?))', 'x = 1 or (x = 2 and x = 3)');
        $this->assertQuery('select * from `foo` where (`foo`.`x` = ? and (`foo`.`x` = ? or `foo`.`x` = ?))', 'x = 1 and (x = 2 or x = 3)');
        $this->assertQuery('select * from `foo` where (`foo`.`x` = ? and (`foo`.`x` = ?))', 'x = 1 and (x = 2)');
    }
}
