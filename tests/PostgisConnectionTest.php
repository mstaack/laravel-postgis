<?php

use Phaza\LaravelPostgis\PostgisConnection;
use Phaza\LaravelPostgis\Schema\Builder;
use Stubs\PDOStub;

class PostgisConnectionTest extends PHPUnit_Framework_TestCase
{
    private $postgisConnection;

    protected function setUp()
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
