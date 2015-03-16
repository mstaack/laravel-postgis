<?php namespace Phaza\LaravelPostgis\Geometries;

class MultiPoint extends PointCollection implements GeometryInterface {

	public function toWKT()
	{
		return sprintf( 'MULTIPOINT(%s)', (string) $this );
	}

	public static function fromWkt( $wkt )
	{
		$wktArgument = Geometry::getWKTArgument( $wkt );

		return static::fromString( $wktArgument );
	}

	public static function fromString( $wktArgument )
	{
		$matches = [ ];
		preg_match_all( '/\(\s*(\d+\s+\d+)\s*\)/', trim( $wktArgument ), $matches );

		if( count( $matches ) < 2 ) {
			return new static( [ ] );
		}

		$points = array_map( function ( $pair ) {
			return Point::fromPair( $pair );
		},
			$matches[1] );

		return new static( $points );
	}

	public function __toString()
	{
		return implode( ',', array_map( function ( Point $point ) {
			return sprintf( '(%s)', $point->toPair() );
		},
			$this->points ) );
	}
}
