<?php

namespace MStaack\LaravelPostgis\Geometries;

class Point extends Geometry
{
    protected $lat;
    protected $lng;
    protected $alt;

    public function __construct($lat, $lng, $alt = null)
    {
        $this->lat = (float)$lat;
        $this->lng = (float)$lng;
        $this->alt = isset($alt) ? (float)$alt : null;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        $this->lat = (float)$lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        $this->lng = (float)$lng;
    }

    public function getAlt()
    {
        return $this->alt;
    }

    public function setAlt($alt)
    {
        $this->alt = (float)$alt;
    }

    public function is3d()
    {
        return isset($this->alt);
    }

    public function toPair()
    {
        $pair = self::stringifyFloat($this->getLng()) . ' ' . self::stringifyFloat($this->getLat());
        if ($this->is3d()) {
            $pair .= ' ' . self::stringifyFloat($this->getAlt());
        }
        return $pair;
    }

    private static function stringifyFloat($float)
    {
        // normalized output among locales
        return rtrim(rtrim(sprintf('%F', $float), '0'), '.');
    }

    public static function fromPair($pair)
    {
        $pair = preg_replace('/^[a-zA-Z\(\)]+/', '', trim($pair));
        $splits = explode(' ', trim($pair));
        $lng = $splits[0];
        $lat = $splits[1];
        if (count($splits) > 2) {
            $alt = $splits[2];
        }

        return new static((float)$lat, (float)$lng, isset($alt) ? (float)$alt : null);
    }

    public function toWKT()
    {
        $wktType = 'POINT';
        if ($this->is3d()) $wktType .= ' Z';
        return sprintf('%s(%s)', $wktType, (string)$this);
    }

    public static function fromString($wktArgument)
    {
        return static::fromPair($wktArgument);
    }

    public function __toString()
    {
        return $this->toPair();
    }

    /**
     * Convert to GeoJson Point that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\Point
     */
    public function jsonSerialize()
    {
        $position = [$this->getLng(), $this->getLat()];
        if ($this->is3d()) $position[] = $this->getAlt();
        return new \GeoJson\Geometry\Point($position);
    }
}
