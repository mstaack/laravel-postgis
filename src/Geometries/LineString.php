<?php namespace Phaza\LaravelPostgis\Geometries;

class LineString extends PointCollection implements GeometryInterface
{
    public function toWKT()
    {
        return sprintf('LINESTRING(%s)', $this->toPairList());
    }

    public static function fromWkt($wkt)
    {
        $wktArgument = Geometry::getWKTArgument($wkt);

        return static::fromString($wktArgument);
    }

    public static function fromString($wktArgument)
    {
        $pairs = explode(',', trim($wktArgument));
        $points = array_map(function ($pair) {
            return Point::fromPair($pair);
        }, $pairs);

        return new static($points);
    }

    public function __toString()
    {
        return $this->toPairList();
    }
}
