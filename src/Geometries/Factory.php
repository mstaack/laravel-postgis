<?php

namespace MStaack\LaravelPostgis\Geometries;

class Factory implements \GeoIO\Factory
{
    public function createPoint($dimension, array $coordinates, $srid = null)
    {
        return $this->hasMeasure($dimension)
            ? new PointM($coordinates['m'], $coordinates['x'], $coordinates['y'], $coordinates['z'] ?? null)
            : new Point($coordinates['y'], $coordinates['x'], $coordinates['z'] ?? null);
    }

    public function createLineString($dimension, array $points, $srid = null)
    {
        return $this->hasMeasure($dimension)
            ? new LineStringM($points)
            : new LineString($points);
    }

    public function createLinearRing($dimension, array $points, $srid = null)
    {
        return $this->hasMeasure($dimension)
            ? new LineStringM($points)
            : new LineString($points);
    }

    public function createPolygon($dimension, array $lineStrings, $srid = null)
    {
        return new Polygon($lineStrings);
    }

    public function createMultiPoint($dimension, array $points, $srid = null)
    {
        return new MultiPoint($points);
    }

    public function createMultiLineString($dimension, array $lineStrings, $srid = null)
    {
        return new MultiLineString($lineStrings);
    }

    public function createMultiPolygon($dimension, array $polygons, $srid = null)
    {
        return new MultiPolygon($polygons);
    }

    public function createGeometryCollection($dimension, array $geometries, $srid = null)
    {
        return new GeometryCollection($geometries);
    }

    /** @return bool */
    public function hasMeasure($dimension)
    {
        return in_array($dimension, ['4D', '3DM']);
    }
}