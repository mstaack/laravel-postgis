<?php

use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\MultiLineString;

class MultiLineStringTest extends BaseTestCase
{
    public function testFromWKT()
    {
        $multilinestring = MultiLineString::fromWKT('MULTILINESTRING((0 0,1 1,1 2),(2 3,3 2,5 4))');
        $this->assertInstanceOf(MultiLineString::class, $multilinestring);

        $this->assertEquals(2, $multilinestring->count());
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

        $multilinestring = new MultiLineString([$collection]);

        $this->assertEquals('MULTILINESTRING((0 0,1 0,1 1,0 1,0 0))', $multilinestring->toWKT());
    }
}
