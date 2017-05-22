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

    /**
     * Convert to GeoJson Polygon that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\Polygon
     */
    public function jsonSerialize()
    {
        $linearrings = [];
        foreach ($this->linestrings as $linestring) {
            $linearrings[] = new \GeoJson\Geometry\LinearRing($linestring->jsonSerialize()->getCoordinates());
        }

        return new \GeoJson\Geometry\Polygon($linearrings);
    }
}
