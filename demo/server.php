<?php

$autoload = __DIR__.'/../vendor/autoload.php';
if (!file_exists($autoload)) {
    throw new RuntimeException('Install dependencies using composer to run the demo.');
}

require_once $autoload;

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
}

$t = new FilterTest();
$t->setUp();

use Railken\LaraEye\Filter;
use Railken\LaraEye\Tests\Foo;

$filter = new Filter();
$filter->setKeys(['id', 'name', 'updated_at', 'created_at']);
$query = \Illuminate\Support\Facades\DB::table('foo');

try {
    $result = $filter->build($query, $_GET['q']);
} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode((object) ['message' => $e->getMessage()]);
    die();
}

echo json_encode((object) ['query' => ['sql' => $query->toSql(), 'params' => $query->getBindings()]]);
