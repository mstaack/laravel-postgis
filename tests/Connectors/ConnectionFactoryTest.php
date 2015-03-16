<?php

use Illuminate\Container\Container;
use Illuminate\Database\MySqlConnection;
use Phaza\LaravelPostgis\Connectors\ConnectionFactory;
use Phaza\LaravelPostgis\PostgisConnection;

class ConnectionFactoryBaseTest extends BaseTestCase {
	public function testMakeCallsCreateConnection()
	{
		$factory = Mockery::mock( ConnectionFactory::class, [ Mockery::mock( Container::class ) ] )
		                  ->makePartial()
		                  ->shouldAllowMockingProtectedMethods();

		$pgConfig = [ 'driver' => 'pgsql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo' ];
		$pdo      = new DatabaseConnectionFactoryPDOStub;

		$factory
			->shouldReceive( 'createConnection' )
			->once()
			->withArgs( [ 'pgsql', $pdo, 'database', 'prefix', $pgConfig ] )
			->andReturn( Mockery::type( PostgisConnection::class ) );


		$myConfig = [ 'driver' => 'mysql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo' ];

		$factory
			->shouldReceive( 'createConnection' )
			->once()
			->withArgs( [ 'mysql', $pdo, 'database', 'prefix', $myConfig ] )
			->andReturn( Mockery::type( MySqlConnection::class ) );
	}
}

class DatabaseConnectionFactoryPDOStub extends PDO {
	public function __construct()
	{
	}
}
