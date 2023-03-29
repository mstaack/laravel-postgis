<?php

namespace MStaack\LaravelPostgis\Geometries;

class PointM extends Point
{
    protected $measure;

    public function __construct($measure, $x, $y, $z = null)
    {
        parent::__construct($y, $x, $z);
        $this->measure = (float) $measure;
    }

    public function setX($x)
    {
        $this->setLng($x);
    }

    public function getX()
    {
        return $this->getLng();
    }

    public function setY($y)
    {
        $this->setLat($y);
    }

    public function getY()
    {
        return $this->getLat();
    }

    public function getZ()
    {
        return $this->getAlt();
    }

    public function setZ($z)
    {
        $this->setAlt($z);
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

        return substr_replace(
            $wktType,
            $this->is3d() ? 'M' : ' M',
            $insertBefore,
            0
        );
    }

    public function toPair()
    {
        return parent::toPair() . ' ' . $this->stringifyFloat($this->getMeasure());
    }

    public static function fromPair($pair)
    {
        $pair = preg_replace('/^[a-zA-Z\(\)]+/', '', trim($pair));
        $splits = explode(' ', trim($pair));
        $measure = array_pop($splits);
        $x = $splits[0];
        $y = $splits[1];
        if (count($splits) > 2) {
            $z = $splits[2];
        }

        return new static((float) $measure, (float) $x, (float) $y, isset($z) ? (float) $z : null);
    }
}