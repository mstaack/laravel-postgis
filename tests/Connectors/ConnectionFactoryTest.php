<?php

use Illuminate\Container\Container;
use Phaza\LaravelPostgis\Connectors\ConnectionFactory;
use Phaza\LaravelPostgis\PostgisConnection;
use Stubs\PDOStub;

class ConnectionFactoryBaseTest extends BaseTestCase
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
