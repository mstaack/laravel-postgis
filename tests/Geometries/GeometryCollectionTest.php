<?php

namespace MStaack\LaravelPostgis\Tests\Geometries;

use MStaack\LaravelPostgis\Geometries\GeometryCollection;
use MStaack\LaravelPostgis\Geometries\LineString;
use MStaack\LaravelPostgis\Geometries\LineStringM;
use MStaack\LaravelPostgis\Geometries\Point;
use MStaack\LaravelPostgis\Geometries\PointM;
use MStaack\LaravelPostgis\Tests\BaseTestCase;

class GeometryCollectionTest extends BaseTestCase
{
    /**
     * @var GeometryCollection
     */
    private $collection;
    private $collection3d;
    private $collection3dm;
    private $collection4d;

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

        $point = new Point(100, 200);

        $this->collection = new GeometryCollection([$collection, $point]);

        $collection = new LineString(
            [
                new Point(1, 1, 1),
                new Point(1, 2, 3),
                new Point(2, 2, 2),
                new Point(2, 1, 0),
                new Point(1, 1, 1)
            ]
        );

        $point = new Point(100, 200, 300);

        $this->collection3d = new GeometryCollection([$collection, $point]);

        $collection = new LineStringM(
            [
                new PointM(1.1, 1, 1),
                new PointM(1.2, 2, 1),
                new PointM(1.3, 2, 2),
                new PointM(1.4, 1, 2),
                new PointM(1.5, 1, 1)
            ]
        );

        $point = new PointM(50, 200, 100);

        $this->collection3dm = new GeometryCollection([$collection, $point]);

        $collection = new LineStringM(
            [
                new PointM(1.1, 1, 1, 1),
                new PointM(1.2, 2, 1, 3),
                new PointM(1.3, 2, 2, 2),
                new PointM(1.4, 1, 2, 0),
                new PointM(1.5, 1, 1, 1)
            ]
        );

        $point = new PointM(50, 200, 100, 300);

        $this->collection4d = new GeometryCollection([$collection, $point]);
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

    public function testFromWKT3dm()
    {
        /**
         * @var GeometryCollection $geometryCollection
         */
        $geometryCollection = GeometryCollection::fromWKT('GEOMETRYCOLLECTION(POINTM(2 3 1.0),LINESTRINGM(2 3 1.1,3 4 1.2))');
        $this->assertInstanceOf(GeometryCollection::class, $geometryCollection);

        $this->assertEquals(2, $geometryCollection->count());
        $this->assertInstanceOf(PointM::class, $geometryCollection->getGeometries()[0]);
        $this->assertInstanceOf(LineStringM::class, $geometryCollection->getGeometries()[1]);
    }

    public function testFromWKT3d()
    {
        /**
         * @var GeometryCollection $geometryCollection
         */
        $geometryCollection = GeometryCollection::fromWKT('GEOMETRYCOLLECTION(POINT Z(2 3 4),LINESTRING Z(2 3 4,3 4 5))');
        $this->assertInstanceOf(GeometryCollection::class, $geometryCollection);

        $this->assertEquals(2, $geometryCollection->count());
        $this->assertInstanceOf(Point::class, $geometryCollection->getGeometries()[0]);
        $this->assertInstanceOf(LineString::class, $geometryCollection->getGeometries()[1]);
    }

    public function testFromWKT4d()
    {
        /**
         * @var GeometryCollection $geometryCollection
         */
        $geometryCollection = GeometryCollection::fromWKT('GEOMETRYCOLLECTION(POINT ZM(2 3 4 1.0),LINESTRING ZM(2 3 4 1.1,3 4 5 1.2))');
        $this->assertInstanceOf(GeometryCollection::class, $geometryCollection);

        $this->assertEquals(2, $geometryCollection->count());
        $this->assertInstanceOf(PointM::class, $geometryCollection->getGeometries()[0]);
        $this->assertInstanceOf(LineStringM::class, $geometryCollection->getGeometries()[1]);
    }

    public function testToWKT()
    {
        $this->assertEquals(
            'GEOMETRYCOLLECTION(LINESTRING(1 1,2 1,2 2,1 2,1 1),POINT(200 100))',
            $this->collection->toWKT()
        );
    }

    public function testToWKT3dm()
    {
        $this->assertEquals(
            'GEOMETRYCOLLECTION(LINESTRING M(1 1 1.1,2 1 1.2,2 2 1.3,1 2 1.4,1 1 1.5),POINT M(200 100 50))',
            $this->collection3dm->toWKT()
        );
    }

    public function testToWKT3d()
    {
        $this->assertEquals(
            'GEOMETRYCOLLECTION(LINESTRING Z(1 1 1,2 1 3,2 2 2,1 2 0,1 1 1),POINT Z(200 100 300))',
            $this->collection3d->toWKT()
        );
    }

    public function testToWKT4d()
    {
        $this->assertEquals(
            'GEOMETRYCOLLECTION(LINESTRING ZM(1 1 1 1.1,2 1 3 1.2,2 2 2 1.3,1 2 0 1.4,1 1 1 1.5),POINT ZM(200 100 300 50))',
            $this->collection4d->toWKT()
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

    public function testJsonSerialize3dm()
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

    public function testJsonSerialize3d()
    {
        $this->assertInstanceOf(
            \GeoJson\Geometry\GeometryCollection::class,
            $this->collection3d->jsonSerialize()
        );

        $this->assertSame(
            '{"type":"GeometryCollection","geometries":[{"type":"LineString","coordinates":[[1,1,1],[2,1,3],[2,2,2],[1,2,0],[1,1,1]]},{"type":"Point","coordinates":[200,100,300]}]}',
            json_encode($this->collection3d->jsonSerialize())
        );
    }

    public function testJsonSerialize4d()
    {
        $this->assertInstanceOf(
            \GeoJson\Geometry\GeometryCollection::class,
            $this->collection3d->jsonSerialize()
        );

        $this->assertSame(
            '{"type":"GeometryCollection","geometries":[{"type":"LineString","coordinates":[[1,1,1],[2,1,3],[2,2,2],[1,2,0],[1,1,1]]},{"type":"Point","coordinates":[200,100,300]}]}',
            json_encode($this->collection4d->jsonSerialize())
        );
    }
}