<?php

namespace MStaack\LaravelPostgis\Geometries;

class MultiLineString extends LineStringCollection
{
    public function toWKT()
    {
        $wktType = 'MULTILINESTRING';
        if ($this->is3d()) $wktType .= ' Z';
        return sprintf('%s(%s)', $wktType, (string)$this);
    }

    /**
     * Convert to GeoJson Point that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\MultiLineString
     */
    public function jsonSerialize(): \GeoJson\Geometry\MultiLineString
    {
        $linestrings = [];

        foreach ($this->linestrings as $linestring) {
            $linestrings[] = $linestring->jsonSerialize();
        }

        return new \GeoJson\Geometry\MultiLineString($linestrings);
    }
}
