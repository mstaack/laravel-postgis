<?php

namespace MStaack\LaravelPostgis\Geometries;

use GeoIO\WKB\Parser\Parser;
use MStaack\LaravelPostgis\Exceptions\UnknownWKTTypeException;

abstract class Geometry implements GeometryInterface, \JsonSerializable
{
    protected static $wkb_types = [
        1 => Point::class,
        2 => LineString::class,
        3 => Polygon::class,
        4 => MultiPoint::class,
        5 => MultiLineString::class,
        6 => MultiPolygon::class,
        7 => GeometryCollection::class,
        8 => PointM::class,
        9 => LineStringM::class
    ];

    public static function getWKTArgument($value)
    {
        $left = strpos($value, '(');
        $right = strrpos($value, ')');

        return substr($value, $left + 1, $right - $left - 1);
    }

    public static function getWKTClass($value)
    {
        $left = strpos($value, '(');
        $type = trim(substr($value, 0, $left));

        switch (strtoupper($type)) {
            case 'POINT':
            case 'POINTZ':
            case 'POINT Z':
                return Point::class;
            case 'POINTM':
            case 'POINT M':
            case 'POINTZM':
            case 'POINT ZM':
                return PointM::class;
            case 'LINESTRING':
            case 'LINESTRINGZ':
            case 'LINESTRING Z':
                return LineString::class;
            case 'LINESTRINGM':
            case 'LINESTRING M':
            case 'LINESTRINGZM':
            case 'LINESTRING ZM':
                return LineStringM::class;
            case 'POLYGON':
            case 'POLYGONZ':
            case 'POLYGON Z':
                return Polygon::class;
            case 'MULTIPOINT':
            case 'MULTIPOINTZ':
            case 'MULTIPOINT Z':
                return MultiPoint::class;
            case 'MULTILINESTRING':
            case 'MULTILINESTRINGZ':
            case 'MULTILINESTRING Z':
                return MultiLineString::class;
            case 'MULTIPOLYGON':
            case 'MULTIPOLYGONZ':
            case 'MULTIPOLYGON Z':
                return MultiPolygon::class;
            case 'GEOMETRYCOLLECTION':
                return GeometryCollection::class;
            default:
                throw new UnknownWKTTypeException('Type was ' . $type);
        }
    }

    public static function fromWKB($wkb)
    {
        $parser = new Parser(new Factory());

        return $parser->parse($wkb);
    }

    public static function fromWKT($wkt)
    {
        $wktArgument = static::getWKTArgument($wkt);

        return static::fromString($wktArgument);
    }
}
