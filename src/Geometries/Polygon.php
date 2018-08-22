<?php namespace Phaza\LaravelPostgis\Geometries;

use Countable;

class Polygon extends MultiLineString implements Countable
{
    public function is3d()
    {
        if(count($this->linestrings) === 0) return false;
        return $this->linestrings[0]->is3d();
    }

    public function toWKT()
    {
        $wktType = 'POLYGON';
        if($this->is3d()) $wktType .= ' Z';
        return sprintf('%s(%s)', $wktType, (string)$this);
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
