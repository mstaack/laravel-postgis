<?php namespace Phaza\LaravelPostgis\Geometries;

use Countable;

class Polygon extends MultiLineString implements Countable
{

    public function toWKT()
    {
        return sprintf('POLYGON((%s))', (string)$this);
    }


    public function __toString()
    {
        return implode( ',', array_map( function ( LineString $linestring ) {
            return sprintf( '%s', (string) $linestring );
        }, $this->getLineStrings() ) );
    }
}
