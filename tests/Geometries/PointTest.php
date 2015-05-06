<?php

use Phaza\LaravelPostgis\Geometries\Geometry;
use Phaza\LaravelPostgis\Geometries\Point;

class PointTest extends BaseTestCase
{
    public function testFromWKT()
    {
        $point = Point::fromWKT('POINT(1 2)');

        $this->assertInstanceOf(Point::class, $point);
        $this->assertEquals(2, $point->getLat());
        $this->assertEquals(1, $point->getLng());
    }

    public function testToWKT()
    {
        $point = new Point(1, 2);

        $this->assertEquals('POINT(2 1)', $point->toWKT());
    }

    public function testToString()
    {
        $point = new Point(1, 2);

        $this->assertEquals('2 1', (string)$point);
    }
}
