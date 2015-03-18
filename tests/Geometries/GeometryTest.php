<?php

use Phaza\LaravelPostgis\Geometries\Geometry;
use Phaza\LaravelPostgis\Geometries\GeometryCollection;
use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\MultiLineString;
use Phaza\LaravelPostgis\Geometries\MultiPoint;
use Phaza\LaravelPostgis\Geometries\MultiPolygon;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;

class GeometryTest extends BaseTestCase {
	public function testGetWKTArgument() {
		$this->assertEquals(
      '0 0',
      Geometry::getWKTArgument('POINT(0 0)')
    );
    $this->assertEquals(
      '0 0,1 1,1 2',
      Geometry::getWKTArgument('LINESTRING(0 0,1 1,1 2)')
    );
    $this->assertEquals(
      '(0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1)',
      Geometry::getWKTArgument('POLYGON((0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1))')
    );
    $this->assertEquals(
      '(0 0),(1 2)',
      Geometry::getWKTArgument('MULTIPOINT((0 0),(1 2))')
    );
    $this->assertEquals(
      '(0 0,1 1,1 2),(2 3,3 2,5 4)',
      Geometry::getWKTArgument('MULTILINESTRING((0 0,1 1,1 2),(2 3,3 2,5 4))')
    );
    $this->assertEquals(
      '((0 0,4 0,4 4,0 4,0 0),(1 1,2 1,2 2,1 2,1 1)), ((-1 -1,-1 -2,-2 -2,-2 -1,-1 -1))',
      Geometry::getWKTArgument('MULTIPOLYGON(((0 0,4 0,4 4,0 4,0 0),(1 1,2 1,2 2,1 2,1 1)), ((-1 -1,-1 -2,-2 -2,-2 -1,-1 -1)))')
    );
    $this->assertEquals(
      'POINT(2 3),LINESTRING(2 3,3 4)',
      Geometry::getWKTArgument('GEOMETRYCOLLECTION(POINT(2 3),LINESTRING(2 3,3 4))')
    );
	}

  public function testGetWKTClass() {
    $this->assertEquals(
      Point::class,
      Geometry::getWKTClass('POINT(0 0)')
    );
    $this->assertEquals(
      LineString::class,
      Geometry::getWKTClass('LINESTRING(0 0,1 1,1 2)')
    );
    $this->assertEquals(
      Polygon::class,
      Geometry::getWKTClass('POLYGON((0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1))')
    );
    $this->assertEquals(
      MultiPoint::class,
      Geometry::getWKTClass('MULTIPOINT((0 0),(1 2))')
    );
    $this->assertEquals(
      MultiLineString::class,
      Geometry::getWKTClass('MULTILINESTRING((0 0,1 1,1 2),(2 3,3 2,5 4))')
    );
    $this->assertEquals(
      MultiPolygon::class,
      Geometry::getWKTClass('MULTIPOLYGON(((0 0,4 0,4 4,0 4,0 0),(1 1,2 1,2 2,1 2,1 1)), ((-1 -1,-1 -2,-2 -2,-2 -1,-1 -1)))')
    );
    $this->assertEquals(
      GeometryCollection::class,
      Geometry::getWKTClass('GEOMETRYCOLLECTION(POINT(2 3),LINESTRING(2 3,3 4))')
    );
  }
}
