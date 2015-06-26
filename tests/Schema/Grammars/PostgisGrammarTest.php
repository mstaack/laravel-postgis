<?php

use Illuminate\Database\Connection;
use Phaza\LaravelPostgis\PostgisConnection;
use Phaza\LaravelPostgis\Schema\Blueprint;
use Phaza\LaravelPostgis\Schema\Grammars\PostgisGrammar;

class PostgisGrammarBaseTest extends BaseTestCase
{
    public function testAddingPoint()
    {
        $blueprint = new Blueprint('test');
        $blueprint->point('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(POINT, 4326)', $statements[0]);
    }

    public function testAddingLinestring()
    {
        $blueprint = new Blueprint('test');
        $blueprint->linestring('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(LINESTRING, 4326)', $statements[0]);
    }

    public function testAddingPolygon()
    {
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(POLYGON, 4326)', $statements[0]);
    }

    public function testAddingMultipoint()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipoint('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(MULTIPOINT, 4326)', $statements[0]);
    }

    public function testAddingMultiLinestring()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multilinestring('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(MULTILINESTRING, 4326)', $statements[0]);
    }

    public function testAddingMultiPolygon()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipolygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(MULTIPOLYGON, 4326)', $statements[0]);
    }

    public function testAddingGeography()
    {
        $blueprint = new Blueprint('test');
        $blueprint->geography('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY', $statements[0]);
    }

    public function testAddingGeometryCollection()
    {
        $blueprint = new Blueprint('test');
        $blueprint->geometrycollection('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('AddGeometryColumn', $statements[0]);
        $this->assertContains('GEOMETRYCOLLECTION', $statements[0]);
    }

    public function testEnablePostgis()
    {
        $blueprint = new Blueprint('test');
        $blueprint->enablePostgis();
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('CREATE EXTENSION postgis', $statements[0]);
    }

    public function testDisablePostgis()
    {
        $blueprint = new Blueprint('test');
        $blueprint->disablePostgis();
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('DROP EXTENSION postgis', $statements[0]);
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return Mockery::mock(PostgisConnection::class);
    }

    protected function getGrammar()
    {
        return new PostgisGrammar();
    }
}
