<?php

namespace MStaack\LaravelPostgis\Geometries;

class LineStringM extends LineString
{
    public function toWKT()
    {
        $wktType = 'LINESTRING';
        $wktType .= $this->is3d() ? ' ZM' : ' M';

        return sprintf('%s(%s)', $wktType, $this->toPairList());
    }

    public static function fromString($wktArgument)
    {
        $pairs = explode(',', trim($wktArgument));
        $points = array_map(function ($pair) {
            return PointM::fromPair($pair);
        }, $pairs);

        return new static($points);
    }
}