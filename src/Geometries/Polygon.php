<?php

namespace MStaack\LaravelPostgis\Geometries;

use GeoJson\Geometry\LinearRing;

class Polygon extends LineStringCollection
{
    public function toWKT()
    {
        $wktType = 'POLYGON';
        if ($this->is3d()) $wktType .= ' Z';
        return sprintf('%s(%s)', $wktType, (string)$this);
    }

    /**
     * Convert to GeoJson Polygon that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\Polygon
     */
    public function jsonSerialize(): \GeoJson\Geometry\Polygon
    {
        $linearrings = [];
        foreach ($this->linestrings as $linestring) {
            $linearrings[] = new LinearRing($linestring->jsonSerialize()->getCoordinates());
        }

        return new \GeoJson\Geometry\Polygon($linearrings);
    }
}
