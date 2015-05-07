<?php

use Phaza\LaravelPostgis\Geometries\GeometryCollection;
use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;

class GeometryCollectionTest extends BaseTestCase
{
    public function testFromWKT()
    {
        /**
         * @var GeometryCollection $geometryCollection
         */
        $geometryCollection = GeometryCollection::fromWKT('GEOMETRYCOLLECTION(POINT(2 3),LINESTRING(2 3,3 4))');
        $this->assertInstanceOf(GeometryCollection::class, $geometryCollection);

        $this->assertEquals(2, $geometryCollection->count());
        $this->assertInstanceOf(Point::class, $geometryCollection->getGeometries()[0]);
        $this->assertInstanceOf(LineString::class, $geometryCollection->getGeometries()[1]);
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

        $point = new Point(100, 200);

        $polygon = new GeometryCollection([$collection, $point]);

        $this->assertEquals('GEOMETRYCOLLECTION(LINESTRING(0 0,1 0,1 1,0 1,0 0),POINT(200 100))', $polygon->toWKT());
    }
}
