<?php namespace Phaza\LaravelPostgis\Geometries;

use Countable;

class Polygon extends MultiLineString implements Countable {

	public function toWKT()
	{
		return sprintf( 'POLYGON(%s)', (string) $this );
	}
}
