<?php

use Illuminate\Container\Container;
use Illuminate\Database\MySqlConnection;
use Phaza\LaravelPostgis\Connectors\ConnectionFactory;
use Phaza\LaravelPostgis\PostgisConnection;

class ConnectionFactoryBaseTest extends BaseTestCase {
	public function testMakeCallsCreateConnection()
	{
		$pgConfig = [ 'driver' => 'pgsql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo' ];
		$pdo      = new DatabaseConnectionFactoryPDOStub;


		$factory = Mockery::mock( ConnectionFactory::class, [ new Container() ] )->makePartial();
		$factory->shouldAllowMockingProtectedMethods();
		$conn    = $factory->createConnection( 'pgsql', $pdo, 'database', 'prefix', $pgConfig );

		$this->assertInstanceOf( PostgisConnection::class, $conn );
	}
}

class DatabaseConnectionFactoryPDOStub extends PDO {
	public function __construct()
	{
	}
}
