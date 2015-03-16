<?php

use Phaza\LaravelPostgis\Geometries\Geometry;
use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;

class LineStringTest extends BaseTestCase {
	public function testFromWKT()
	{
		$linestring = LineString::fromWKT( 'LINESTRING(0 0, 1 1, 2 2)' );
		$this->assertInstanceOf( LineString::class, $linestring );

		$this->assertEquals(3, $linestring->count());
	}

	public function testToWKT() {

		$points = [new Point(0,0), new Point(1,1), new Point(2,2)];
		$linestring = new LineString($points);

		$this->assertEquals( 'LINESTRING(0 0,1 1,2 2)', $linestring->toWKT() );
	}

	public function testToString() {
		$points = [new Point(0,0), new Point(1,1), new Point(2,2)];
		$linestring = new LineString($points);

		$this->assertEquals('0 0,1 1,2 2', (string)$linestring);
	}
}
