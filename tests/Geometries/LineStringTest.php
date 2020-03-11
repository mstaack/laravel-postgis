<?php

namespace MStaack\LaravelPostgis\Tests\Geometries;

use MStaack\LaravelPostgis\Geometries\LineString;
use MStaack\LaravelPostgis\Geometries\Point;
use MStaack\LaravelPostgis\Tests\BaseTestCase;

class LineStringTest extends BaseTestCase
{
    private $points;
    private $points3d;

    protected function setUp(): void
    {
        $this->points = [new Point(1, 1), new Point(2, 2), new Point(3, 3)];
        $this->points3d = [new Point(1, 1, 1), new Point(2, 2, 2), new Point(3, 3, 3)];
    }

    public function testToWKT()
    {
        $linestring = new LineString($this->points);

        $this->assertEquals('LINESTRING(1 1,2 2,3 3)', $linestring->toWKT());
    }

    public function testToWKT3d()
    {
        $linestring = new LineString($this->points3d);

        $this->assertEquals('LINESTRING Z(1 1 1,2 2 2,3 3 3)', $linestring->toWKT());
    }

    public function testFromWKT()
    {
        $linestring = LineString::fromWKT('LINESTRING(1 1, 2 2,3 3)');
        $this->assertInstanceOf(LineString::class, $linestring);

        $this->assertEquals(3, $linestring->count());
    }

    public function testFromWKT3d()
    {
        $linestring = LineString::fromWKT('LINESTRING Z(1 1 1, 2 2 2,3 3 3)');
        $this->assertInstanceOf(LineString::class, $linestring);

        $this->assertEquals(3, $linestring->count());
    }

    public function testToString()
    {
        $linestring = new LineString($this->points);

        $this->assertEquals('1 1,2 2,3 3', (string)$linestring);
    }

    public function testToString3d()
    {
        $linestring = new LineString($this->points3d);

        $this->assertEquals('1 1 1,2 2 2,3 3 3', (string)$linestring);
    }

    public function testJsonSerialize()
    {
        $lineString = new LineString($this->points);

        $this->assertInstanceOf(\GeoJson\Geometry\LineString::class, $lineString->jsonSerialize());
        $this->assertSame('{"type":"LineString","coordinates":[[1,1],[2,2],[3,3]]}', json_encode($lineString));
    }

    public function testJsonSerialize3d()
    {
        $lineString = new LineString($this->points3d);

        $this->assertInstanceOf(\GeoJson\Geometry\LineString::class, $lineString->jsonSerialize());
        $this->assertSame('{"type":"LineString","coordinates":[[1,1,1],[2,2,2],[3,3,3]]}', json_encode($lineString));
    }
}
