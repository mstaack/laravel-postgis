<?php

use Illuminate\Database\Connection;
use Phaza\LaravelPostgis\PostgisConnection;
use Phaza\LaravelPostgis\Schema\Blueprint;
use Phaza\LaravelPostgis\Schema\Grammars\PostgisGrammar;
use Phaza\LaravelPostgis\Exceptions\PostgisTypesMalformedException;
use Phaza\LaravelPostgis\Exceptions\UnsupportedGeomtypeException;

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

    public function testAddingPointGeom()
    {
        $blueprint = new Blueprint('test');
        $blueprint->point('foo', 'GEOMETRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOMETRY(POINT, 27700)', $statements[0]);
    }

    public function testAddingPointWrongSrid()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->point('foo', 'GEOGRAPHY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingPointUnsupported()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->point('foo', 'UNSUPPORTED_ENTRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingLinestring()
    {
        $blueprint = new Blueprint('test');
        $blueprint->linestring('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(LINESTRING, 4326)', $statements[0]);
    }

    public function testAddingLinestringGeom()
    {
        $blueprint = new Blueprint('test');
        $blueprint->linestring('foo', 'GEOMETRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOMETRY(LINESTRING, 27700)', $statements[0]);
    }

    public function testAddingLinestringWrongSrid()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->linestring('foo', 'GEOGRAPHY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingLinestringUnsupported()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->linestring('foo', 'UNSUPPORTED_ENTRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingPolygon()
    {
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(POLYGON, 4326)', $statements[0]);
    }

    public function testAddingPolygonGeom()
    {
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo', 'GEOMETRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOMETRY(POLYGON, 27700)', $statements[0]);
    }

    public function testAddingPolygonWrongSrid()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo', 'GEOGRAPHY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingPolygonUnsupported()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo', 'UNSUPPORTED_ENTRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingMultipoint()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipoint('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(MULTIPOINT, 4326)', $statements[0]);
    }

    public function testAddingMultipointGeom()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipoint('foo', 'GEOMETRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOMETRY(MULTIPOINT, 27700)', $statements[0]);
    }

    public function testAddingMultiPointWrongSrid()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->multipoint('foo', 'GEOGRAPHY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingMultiPointUnsupported()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->multipoint('foo', 'UNSUPPORTED_ENTRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingMultiLinestring()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multilinestring('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(MULTILINESTRING, 4326)', $statements[0]);
    }

    public function testAddingMultiLinestringGeom()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multilinestring('foo', 'GEOMETRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOMETRY(MULTILINESTRING, 27700)', $statements[0]);
    }

    public function testAddingMultiLinestringWrongSrid()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->multilinestring('foo', 'GEOGRAPHY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingMultiLinestringUnsupported()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->multilinestring('foo', 'UNSUPPORTED_ENTRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingMultiPolygon()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipolygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY(MULTIPOLYGON, 4326)', $statements[0]);
    }

    public function testAddingMultiPolygonGeom()
    {
        $blueprint = new Blueprint('test');
        $blueprint->multipolygon('foo', 'GEOMETRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOMETRY(MULTIPOLYGON, 27700)', $statements[0]);
    }

    public function testAddingMultiPolygonWrongSrid()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->multipolygon('foo', 'GEOGRAPHY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingMultiPolygonUnsupported()
    {
        $this->setExpectedException(UnsupportedGeomtypeException::class);
        $blueprint = new Blueprint('test');
        $blueprint->multipolygon('foo', 'UNSUPPORTED_ENTRY', 27700);
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
    }

    public function testAddingGeography()
    {
        $blueprint = new Blueprint('test');
        $blueprint->geography('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOGRAPHY', $statements[0]);
    }

    public function testAddingGeometry()
    {
        $blueprint = new Blueprint('test');
        $blueprint->geometry('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());
        $this->assertEquals(1, count($statements));
        $this->assertContains('GEOMETRY', $statements[0]);
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
