<?php

namespace MStaack\LaravelPostgis\Tests\Schema;

use Mockery;
use MStaack\LaravelPostgis\PostgisConnection;
use MStaack\LaravelPostgis\Schema\Blueprint;
use MStaack\LaravelPostgis\Schema\Builder;
use MStaack\LaravelPostgis\Tests\BaseTestCase;

class BuilderTest extends BaseTestCase
{
    public function testReturnsCorrectBlueprint()
    {
        $connection = Mockery::mock(PostgisConnection::class);
        $connection->shouldReceive('getSchemaGrammar')->once()->andReturn(null);

        $mock = Mockery::mock(Builder::class, [$connection]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();
        $blueprint = $mock->createBlueprint('test', function () {
        });

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
