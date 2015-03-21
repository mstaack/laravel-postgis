<?php namespace Schema;

use BaseTestCase;
use Mockery;
use Phaza\LaravelPostgis\Connection;
use Phaza\LaravelPostgis\Schema\Builder;
use Phaza\LaravelPostgis\Schema\Blueprint;

class BuilderTest extends BaseTestCase {
	public function testReturnsCorrectBlueprint()
	{
		$connection = Mockery::mock( Connection::class );
		$connection->shouldReceive('getSchemaGrammar')->once()->andReturn(null);

		$mock = Mockery::mock( Builder::class, [ $connection ] );
		$mock->makePartial()->shouldAllowMockingProtectedMethods();
		$blueprint = $mock->createBlueprint('test', function() {});

		$this->assertInstanceOf( Blueprint::class, $blueprint );
	}

}
