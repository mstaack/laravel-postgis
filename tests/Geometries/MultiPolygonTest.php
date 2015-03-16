<?php

use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\MultiPolygon;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;

class MultiPolygonTest extends BaseTestCase {
	public function testFromWKT()
	{
		$polygon = MultiPolygon::fromWKT(
			'MULTIPOLYGON(((0 0,4 0,4 4,0 4,0 0),(1 1,2 1,2 2,1 2,1 1)), ((-1 -1,-1 -2,-2 -2,-2 -1,-1 -1)))'
		);
		$this->assertInstanceOf( MultiPolygon::class, $polygon );

		$this->assertEquals( 2, $polygon->count() );
	}

	public function testToWKT()
	{
		$collection1 = new LineString(
			[
				new Point( 0, 0 ),
				new Point( 0, 1 ),
				new Point( 1, 1 ),
				new Point( 1, 0 ),
				new Point( 0, 0 )
			]
		);

		$collection2 = new LineString(
			[
				new Point( 10, 10 ),
				new Point( 10, 20 ),
				new Point( 20, 20 ),
				new Point( 20, 10 ),
				new Point( 10, 10 )
			]
		);

		$polygon1 = new Polygon( [ $collection1, $collection2 ] );

		$collection3 = new LineString(
			[
				new Point( 100, 100 ),
				new Point( 100, 200 ),
				new Point( 200, 200 ),
				new Point( 200, 100 ),
				new Point( 100, 100 )
			]
		);


		$polygon2 = new Polygon( [ $collection3 ] );

		$multipolygon = new MultiPolygon( [ $polygon1, $polygon2 ] );

		$this->assertEquals(
			'MULTIPOLYGON(((0 0,1 0,1 1,0 1,0 0),(10 10,20 10,20 20,10 20,10 10)),((100 100,200 100,200 200,100 200,100 100)))',
			$multipolygon->toWKT()
		);
	}
}
