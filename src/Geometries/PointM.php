<?php

namespace MStaack\LaravelPostgis\Geometries;

class PointM extends Point
{
    protected $measure;

    public function __construct($lat, $lng, $measure, $alt = null)
    {
        parent::__construct($lat, $lng, $alt);
        $this->measure = (float) $measure;
    }

    public function setMeasure($measure)
    {
        $this->measure = (float) $measure;
    }

    public function getMeasure()
    {
        return $this->measure;
    }

    public function toWKT()
    {
        $wktType = parent::toWKT();
        $insertBefore = mb_strpos($wktType, '(');

        return substr_replace($wktType, 'M', $insertBefore, 0);
    }

    public function toPair()
    {
        $points = array_filter([
            $this->getLng(),
            $this->getLat(),
            $this->is3d() ? $this->getAlt() : null,
            $this->getMeasure(),
        ]);

        $pair = array_map(function ($point) {
            return $this->stringifyFloat($point);
        },
            $points
        );

        return implode(' ', $pair);
    }

    public static function fromPair($pair)
    {
        $pair = preg_replace('/^[a-zA-Z\(\)]+/', '', trim($pair));
        $splits = explode(' ', trim($pair));
        $measure = array_pop($splits);
        $lng = $splits[0];
        $lat = $splits[1];
        if (count($splits) > 2) {
            $alt = $splits[2];
        }

        return new static((float) $lat, (float) $lng, (float) $measure, isset($alt) ? (float) $alt : null);
    }
}