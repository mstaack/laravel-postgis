<?php namespace Phaza\LaravelPostgis\Geometries;

use Phaza\LaravelPostgis\Exceptions\UnknownWKTTypeException;

abstract class Geometry implements GeometryInterface {
	public static function getWKTArgument( $value )
	{
		$left  = strpos( $value, '(' );
		$right = strrpos( $value, ')' );

		return substr( $value, $left + 1, $right - $left - 1 );
	}

	public static function getWKTClass( $value )
	{
		$left = strpos( $value, '(' );
		$type = trim(substr( $value, 0, $left ));

		switch( strtoupper( $type ) ) {
			case 'POINT':
				return Point::class;
			case 'LINESTRING':
				return LineString::class;
			case 'POLYGON':
				return Polygon::class;
			case 'MULTIPOINT':
				return MultiPoint::class;
			case 'MULTILINESTRING':
				return MultiLineString::class;
			case 'MULTIPOLYGON':
				return MultiPolygon::class;
			case 'GEOMETRYCOLLECTION':
				return GeometryCollection::class;
			default:
				throw new UnknownWKTTypeException( 'Type was ' . $type );
		}
	}

	public static function fromWKT($wkt) {
		$wktArgument = static::getWKTArgument( $wkt );

		return static::fromString( $wktArgument );
	}
}
