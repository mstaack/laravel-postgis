<?php

use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;

class PolygonTest extends BaseTestCase
{
    private $polygon;

    protected function setUp()
    {
        $collection = new LineString(
            [
                new Point(1, 1),
                new Point(1, 2),
                new Point(2, 2),
                new Point(2, 1),
                new Point(1, 1)
            ]
        );

        $this->polygon = new Polygon([$collection]);
    }


    public function testFromWKT()
    {
        $wkt = 'POLYGON((1 1,5 1,5 5,1 5,1 1),(2 2,3 2,3 3,2 3,2 2))';
        $polygon = Polygon::fromWKT($wkt);
        $this->assertInstanceOf(Polygon::class, $polygon);

        $this->assertEquals(2, $polygon->count());
        $this->assertEquals($wkt, $polygon->toWKT());
    }

    public function testToWKT()
    {
        $this->assertEquals('POLYGON((1 1,2 1,2 2,1 2,1 1))', $this->polygon->toWKT());
    }

    public function testJsonSerialize()
    {
        $this->assertInstanceOf(\GeoJson\Geometry\Polygon::class, $this->polygon->jsonSerialize());
        $this->assertSame(
            '{"type":"Polygon","coordinates":[[[1,1],[2,1],[2,2],[1,2],[1,1]]]}',
            json_encode($this->polygon)
        );

    }
}
