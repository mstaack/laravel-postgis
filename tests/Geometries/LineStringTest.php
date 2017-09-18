<?php

use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;

class LineStringTest extends BaseTestCase
{
    private $points;

    protected function setUp()
    {
        $this->points = [new Point(1, 1), new Point(2, 2), new Point(3, 3)];
    }

    public function testToWKT()
    {
        $linestring = new LineString($this->points);

        $this->assertEquals('LINESTRING(1 1,2 2,3 3)', $linestring->toWKT());
    }

    public function testFromWKT()
    {
        $linestring = LineString::fromWKT('LINESTRING(1 1, 2 2,3 3)');
        $this->assertInstanceOf(LineString::class, $linestring);

        $this->assertEquals(3, $linestring->count());
    }

    public function testToString()
    {
        $linestring = new LineString($this->points);

        $this->assertEquals('1 1,2 2,3 3', (string)$linestring);
    }

    public function testJsonSerialize()
    {
        $lineString = new LineString($this->points);

        $this->assertInstanceOf(\GeoJson\Geometry\LineString::class, $lineString->jsonSerialize());
        $this->assertSame('{"type":"LineString","coordinates":[[1,1],[2,2],[3,3]]}', json_encode($lineString));
    }
}
