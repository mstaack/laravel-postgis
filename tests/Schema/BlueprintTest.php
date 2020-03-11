<?php

namespace MStaack\LaravelPostgis\Tests\Schema;

use Mockery;
use MStaack\LaravelPostgis\Schema\Blueprint;
use MStaack\LaravelPostgis\Tests\BaseTestCase;

class BlueprintTest extends BaseTestCase
{
    protected $blueprint;

    public function setUp(): void
    {
        parent::setUp();

        $this->blueprint = Mockery::mock(Blueprint::class)
            ->makePartial()->shouldAllowMockingProtectedMethods();
    }

    public function testMultiPoint()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('multipoint', ['col', null, 2, true]);

        $this->blueprint->multipoint('col');
    }

    public function testPolygon()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('polygon', ['col', null, 2, true]);

        $this->blueprint->polygon('col');
    }

    public function testMulltiPolygon()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('multipolygon', ['col', null, 2, true]);

        $this->blueprint->multipolygon('col');
    }

    public function testLineString()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('linestring', ['col', null, 2, true]);

        $this->blueprint->linestring('col');
    }

    public function testMultiLineString()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('multilinestring', ['col', null, 2, true]);

        $this->blueprint->multilinestring('col');
    }

    public function testGeography()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('geography', ['col', null, 2, true]);

        $this->blueprint->geography('col');
    }

    public function testGeometryCollection()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('geometrycollection', ['col', null, 2, true]);

        $this->blueprint->geometrycollection('col');
    }

    public function testEnablePostgis()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('enablePostgis', []);

        $this->blueprint->enablePostgis();
    }

    public function testDisablePostgis()
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('disablePostgis', []);

        $this->blueprint->disablePostgis();
    }
}
