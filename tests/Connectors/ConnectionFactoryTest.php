<?php

namespace MStaack\LaravelPostgis\Tests;

use Illuminate\Container\Container;
use Mockery;
use MStaack\LaravelPostgis\Connectors\ConnectionFactory;
use MStaack\LaravelPostgis\PostgisConnection;
use MStaack\LaravelPostgis\Tests\Stubs\PDOStub;

class ConnectionFactoryTest extends BaseTestCase
{
    public function testMakeCallsCreateConnection()
    {
        $pgConfig = ['driver' => 'pgsql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $pdo = new PDOStub();

        $factory = Mockery::mock(ConnectionFactory::class, [new Container()])->makePartial();
        $factory->shouldAllowMockingProtectedMethods();
        $conn = $factory->createConnection('pgsql', $pdo, 'database', 'prefix', $pgConfig);

        $this->assertInstanceOf(PostgisConnection::class, $conn);
    }
}
