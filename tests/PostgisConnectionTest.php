<?php

use MStaack\LaravelPostgis\PostgisConnection;
use MStaack\LaravelPostgis\Schema\Builder;
use Stubs\PDOStub;

class PostgisConnectionTest extends BaseTestCase
{
    private $postgisConnection;

    protected function setUp(): void
    {
        $pgConfig = ['driver' => 'pgsql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $this->postgisConnection = new PostgisConnection(new PDOStub(), 'database', 'prefix', $pgConfig);
    }

    public function testGetSchemaBuilder()
    {
        $builder = $this->postgisConnection->getSchemaBuilder();

        $this->assertInstanceOf(Builder::class, $builder);
    }
}
