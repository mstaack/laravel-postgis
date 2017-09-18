<?php

use Phaza\LaravelPostgis\Geometries\GeometryCollection;
use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;

class GeometryCollectionTest extends BaseTestCase
{
    /**
     * @var GeometryCollection
     */
    private $collection;

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

        $point = new Point(100, 200);

        $this->collection = new GeometryCollection([$collection, $point]);
    }


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
        $this->assertEquals(
            'GEOMETRYCOLLECTION(LINESTRING(1 1,2 1,2 2,1 2,1 1),POINT(200 100))',
            $this->collection->toWKT()
        );
    }

    public function testJsonSerialize()
    {
        $this->assertInstanceOf(
            \GeoJson\Geometry\GeometryCollection::class,
            $this->collection->jsonSerialize()
        );

        $this->assertSame(
            '{"type":"GeometryCollection","geometries":[{"type":"LineString","coordinates":[[1,1],[2,1],[2,2],[1,2],[1,1]]},{"type":"Point","coordinates":[200,100]}]}',
            json_encode($this->collection->jsonSerialize())
        );

    }
}
