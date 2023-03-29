<?php

namespace MStaack\LaravelPostgis\Tests\Geometries;

use MStaack\LaravelPostgis\Geometries\PointM;
use MStaack\LaravelPostgis\Tests\BaseTestCase;

class PointMTest extends BaseTestCase
{
    public function testFromWKT()
    {
        $point = PointM::fromWKT('POINTM(1 2 0.3)');

        $this->assertInstanceOf(PointM::class, $point);
        $this->assertEquals(1, $point->getX());
        $this->assertEquals(1, $point->getLng());
        $this->assertEquals(2, $point->getY());
        $this->assertEquals(2, $point->getLat());
        $this->assertEquals(0.3, $point->getMeasure());
    }

    public function testFromWKT3d()
    {
        $point = PointM::fromWKT('POINT ZM(1 2 3 0.4)');

        $this->assertInstanceOf(PointM::class, $point);
        $this->assertEquals(1, $point->getX());
        $this->assertEquals(1, $point->getLng());
        $this->assertEquals(2, $point->getY());
        $this->assertEquals(2, $point->getLat());
        $this->assertEquals(3, $point->getAlt());
        $this->assertEquals(3, $point->getZ());
        $this->assertEquals(0.4, $point->getMeasure());
    }

    public function testToWKT()
    {
        $point = new PointM(0.3, 1, 2);

        $this->assertEquals('POINT M(1 2 0.3)', $point->toWKT());
    }

    public function testToWKT3d()
    {
        $point = new PointM(0.4, 1, 2, 3);

        $this->assertEquals('POINT ZM(1 2 3 0.4)', $point->toWKT());
    }

    public function testGettersAndSetters()
    {
        $point = new PointM(0.3, 1, 2);
        $this->assertEquals(1, $point->getX());
        $this->assertEquals(1, $point->getLng());
        $this->assertEquals(2, $point->getY());
        $this->assertEquals(2, $point->getLat());
        $this->assertEquals(0.3, $point->getMeasure());
        $this->assertNull($point->getZ());
        $this->assertNull($point->getAlt());

        $point->setX('4');
        $point->setY('5');
        $point->setMeasure('0.6');

        $this->assertEquals(4, $point->getX());
        $this->assertEquals(4, $point->getLng());
        $this->assertEquals(5, $point->getY());
        $this->assertEquals(5, $point->getLat());
        $this->assertEquals(0.6, $point->getMeasure());
        $this->assertNull($point->getZ());
        $this->assertNull($point->getAlt());

        $point->setLat('7');
        $point->setLng('8');
        $this->assertEquals(8, $point->getX());
        $this->assertEquals(8, $point->getLng());
        $this->assertEquals(7, $point->getY());
        $this->assertEquals(7, $point->getLat());
        $this->assertEquals(0.6, $point->getMeasure());
        $this->assertNull($point->getZ());
        $this->assertNull($point->getAlt());
    }

    public function testGettersAndSetters4d()
    {
        $point = new PointM(0.4, 1, 2, 3);
        $this->assertEquals(1, $point->getX());
        $this->assertEquals(1, $point->getLng());
        $this->assertEquals(2, $point->getY());
        $this->assertEquals(2, $point->getLat());
        $this->assertEquals(3, $point->getZ());
        $this->assertEquals(3, $point->getAlt());
        $this->assertEquals(0.4, $point->getMeasure());

        $point->setX('4');
        $point->setY('5');
        $point->setZ('6');
        $point->setMeasure('0.7');

        $this->assertEquals(4, $point->getX());
        $this->assertEquals(4, $point->getLng());
        $this->assertEquals(5, $point->getY());
        $this->assertEquals(5, $point->getLat());
        $this->assertEquals(6, $point->getZ());
        $this->assertEquals(6, $point->getAlt());
        $this->assertEquals(0.7, $point->getMeasure());

        $point->setLat('8');
        $point->setLng('9');
        $point->setAlt('10');
        $this->assertEquals(9, $point->getX());
        $this->assertEquals(9, $point->getLng());
        $this->assertEquals(8, $point->getY());
        $this->assertEquals(8, $point->getLat());
        $this->assertEquals(0.7, $point->getMeasure());
        $this->assertEquals(10, $point->getZ());
        $this->assertEquals(10, $point->getAlt());
    }

    public function testPair()
    {
        $point = PointM::fromPair('1.5 2 3.1');

        $this->assertSame(1.5, $point->getX());
        $this->assertSame(1.5, $point->getLng());
        $this->assertSame(2.0, $point->getY());
        $this->assertSame(2.0, $point->getLat());
        $this->assertSame(3.1, $point->getMeasure());

        $this->assertSame('1.5 2 3.1', $point->toPair());
    }

    public function testPair4d()
    {
        $point = PointM::fromPair('1.5 2 2.5 3.1');

        $this->assertSame(1.5, $point->getX());
        $this->assertSame(1.5, $point->getLng());
        $this->assertSame(2.0, $point->getY());
        $this->assertSame(2.0, $point->getLat());
        $this->assertSame(2.5, $point->getZ());
        $this->assertSame(2.5, $point->getAlt());
        $this->assertSame(3.1, $point->getMeasure());

        $this->assertSame('1.5 2 2.5 3.1', $point->toPair());
    }

    public function testToString()
    {
        $point = PointM::fromString('1.3 2 3.1');

        $this->assertSame(1.3, $point->getX());
        $this->assertSame(1.3, $point->getLng());
        $this->assertSame(2.0, $point->getY());
        $this->assertSame(2.0, $point->getLat());
        $this->assertSame(3.1, $point->getMeasure());
        $this->assertNull($point->getZ());
        $this->assertNull($point->getAlt());

        $this->assertEquals('1.3 2 3.1', (string) $point);
    }

    public function testToString3d()
    {
        $point = PointM::fromString('1.3 2 2.3 3.1');

        $this->assertSame(1.3, $point->getX());
        $this->assertSame(1.3, $point->getLng());
        $this->assertSame(2.0, $point->getY());
        $this->assertSame(2.0, $point->getLat());
        $this->assertSame(2.3, $point->getAlt());
        $this->assertSame(2.3, $point->getZ());
        $this->assertSame(3.1, $point->getMeasure());

        $this->assertEquals('1.3 2 2.3 3.1', (string) $point);
    }

    public function testJsonSerialize()
    {
        $point = new PointM(2.3, 1.2, 3.4);

        $this->assertInstanceOf(\GeoJson\Geometry\Point::class, $point->jsonSerialize());
        $this->assertSame('{"type":"Point","coordinates":[1.2,3.4]}', json_encode($point));
    }

    public function testJsonSerialize4d()
    {
        $point = new PointM(2.3, 1.2, 3.4, 5.6);

        $this->assertInstanceOf(\GeoJson\Geometry\Point::class, $point->jsonSerialize());
        $this->assertSame('{"type":"Point","coordinates":[1.2,3.4,5.6]}', json_encode($point));
    }

    public function testPointPrecisionDefault()
    {
        $point = new PointM(-50.00000000004, -37.8745505, 144.9102885, 12.38);

        $this->assertSame('-37.87455 144.910289 12.38 -50', $point->toPair());
    }

    public function testPointPrecision10()
    {
        $point = new PointM(-50.00000000006, -37.87455051578, 144.91028850798, 7.38257341563);
        $point->setPrecision(10);

        $this->assertSame('-37.8745505158 144.910288508 7.3825734156 -50.0000000001', $point->toPair());
    }
}