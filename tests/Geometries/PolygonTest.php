<?php

namespace MStaack\LaravelPostgis\Tests\Geometries;

use MStaack\LaravelPostgis\Geometries\LineString;
use MStaack\LaravelPostgis\Geometries\Point;
use MStaack\LaravelPostgis\Geometries\Polygon;
use MStaack\LaravelPostgis\Tests\BaseTestCase;

class PolygonTest extends BaseTestCase
{
    private $polygon;
    private $polygon3d;

    protected function setUp(): void
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

        $collection = new LineString(
            [
                new Point(1, 1, 1),
                new Point(1, 2, 2),
                new Point(2, 2, 2),
                new Point(2, 1, 2),
                new Point(1, 1, 1)
            ]
        );

        $this->polygon3d = new Polygon([$collection]);
    }


    public function testFromWKT()
    {
        $wkt = 'POLYGON((1 1,5 1,5 5,1 5,1 1),(2 2,3 2,3 3,2 3,2 2))';
        $polygon = Polygon::fromWKT($wkt);
        $this->assertInstanceOf(Polygon::class, $polygon);

        $this->assertEquals(2, $polygon->count());
        $this->assertEquals($wkt, $polygon->toWKT());
    }

    public function testFromWKT3d()
    {
        $wkt = 'POLYGON Z((1 1 1,5 1 1,5 5 1,1 5 1,1 1 1),(2 2 2,3 2 2,3 3 2,2 3 2,2 2 2))';
        $polygon = Polygon::fromWKT($wkt);
        $this->assertInstanceOf(Polygon::class, $polygon);

        $this->assertEquals(2, $polygon->count());
        $this->assertEquals($wkt, $polygon->toWKT());
    }

    public function testToWKT()
    {
        $this->assertEquals('POLYGON((1 1,2 1,2 2,1 2,1 1))', $this->polygon->toWKT());
    }

    public function testToWKT3d()
    {
        $this->assertEquals('POLYGON Z((1 1 1,2 1 2,2 2 2,1 2 2,1 1 1))', $this->polygon3d->toWKT());
    }

    public function testJsonSerialize()
    {
        $this->assertInstanceOf(\GeoJson\Geometry\Polygon::class, $this->polygon->jsonSerialize());
        $this->assertSame(
            '{"type":"Polygon","coordinates":[[[1,1],[2,1],[2,2],[1,2],[1,1]]]}',
            json_encode($this->polygon)
        );

    }

    public function testJsonSerialize3d()
    {
        $this->assertInstanceOf(\GeoJson\Geometry\Polygon::class, $this->polygon3d->jsonSerialize());
        $this->assertSame(
            '{"type":"Polygon","coordinates":[[[1,1,1],[2,1,2],[2,2,2],[1,2,2],[1,1,1]]]}',
            json_encode($this->polygon3d)
        );

    }
}
