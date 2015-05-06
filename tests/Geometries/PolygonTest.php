<?php

use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;

class PolygonTest extends BaseTestCase
{
    public function testFromWKT()
    {
        $polygon = Polygon::fromWKT('POLYGON((0 0,4 0,4 4,0 4,0 0),(1 1, 2 1, 2 2, 1 2,1 1))');
        $this->assertInstanceOf(Polygon::class, $polygon);

        $this->assertEquals(2, $polygon->count());
    }

    public function testToWKT()
    {
        $collection = new LineString(
            [
                new Point(0, 0),
                new Point(0, 1),
                new Point(1, 1),
                new Point(1, 0),
                new Point(0, 0)
            ]
        );

        $polygon = new Polygon([$collection]);

        $this->assertEquals('POLYGON((0 0,1 0,1 1,0 1,0 0))', $polygon->toWKT());
    }
}
