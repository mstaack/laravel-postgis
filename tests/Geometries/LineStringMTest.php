<?php

namespace MStaack\LaravelPostgis\Tests\Geometries;

use MStaack\LaravelPostgis\Geometries\LineStringM;
use MStaack\LaravelPostgis\Geometries\PointM;
use MStaack\LaravelPostgis\Tests\BaseTestCase;

class LineStringMTest extends BaseTestCase
{
    private $points3dm;
    private $points4d;

    protected function setUp(): void
    {
        $this->points3dm = [new PointM(1, 1, 1), new PointM(2, 2, 2), new PointM(3, 3, 3)];
        $this->points4d = [new PointM(1, 1, 1, 1), new PointM(2, 2, 2, 2), new PointM(3, 3, 3, 3)];
    }

    public function testToWKT()
    {
        $linestring = new LineStringM($this->points3dm);

        $this->assertEquals('LINESTRING M(1 1 1,2 2 2,3 3 3)', $linestring->toWKT());
    }

    public function testToWKT3d()
    {
        $linestring = new LineStringM($this->points4d);

        $this->assertEquals('LINESTRING ZM(1 1 1 1,2 2 2 2,3 3 3 3)', $linestring->toWKT());
    }

    public function testFromWKT()
    {
        $linestring = LineStringM::fromWKT('LINESTRING M(1 1 1, 2 2 2,3 3 3)');
        $this->assertInstanceOf(LineStringM::class, $linestring);

        $this->assertEquals(3, $linestring->count());
    }

    public function testFromWKT3d()
    {
        $linestring = LineStringM::fromWKT('LINESTRING ZM(1 1 1 1, 2 2 2 2,3 3 3 3)');
        $this->assertInstanceOf(LineStringM::class, $linestring);

        $this->assertEquals(3, $linestring->count());
    }

    public function testToString()
    {
        $linestring = new LineStringM($this->points3dm);

        $this->assertEquals('1 1 1,2 2 2,3 3 3', (string) $linestring);
    }

    public function testToString3d()
    {
        $linestring = new LineStringM($this->points4d);

        $this->assertEquals('1 1 1 1,2 2 2 2,3 3 3 3', (string) $linestring);
    }

    public function testJsonSerialize()
    {
        $lineString = new LineStringM($this->points3dm);

        $this->assertInstanceOf(\GeoJson\Geometry\LineString::class, $lineString->jsonSerialize());
        $this->assertSame('{"type":"LineString","coordinates":[[1,1],[2,2],[3,3]]}', json_encode($lineString));
    }

    public function testJsonSerialize3d()
    {
        $lineString = new LineStringM($this->points4d);

        $this->assertInstanceOf(\GeoJson\Geometry\LineString::class, $lineString->jsonSerialize());
        $this->assertSame('{"type":"LineString","coordinates":[[1,1,1],[2,2,2],[3,3,3]]}', json_encode($lineString));
    }
}