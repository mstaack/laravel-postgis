<?php

namespace MStaack\LaravelPostgis\Geometries;

class Point extends Geometry
{
    protected $lat;
    protected $lng;
    protected $alt;
    protected $precision;

    public function __construct($lat, $lng, $alt = null)
    {
        $this->lat = (float)$lat;
        $this->lng = (float)$lng;
        $this->alt = isset($alt) ? (float)$alt : null;
        $this->setPrecision(
            function_exists('config') ? config('postgis.precision', 6) : 6
        );
    }

    public function setPrecision($precision)
    {
        $precision = filter_var($precision, FILTER_VALIDATE_INT);
        if (!is_int($precision)) {
            throw new \UnexpectedValueException('Precision must be an integer');
        }

        $this->precision = $precision;
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
        $pair = $this->stringifyFloat($this->getLng()) . ' ' . $this->stringifyFloat($this->getLat());
        if ($this->is3d()) {
            $pair .= ' ' . $this->stringifyFloat($this->getAlt());
        }
        return $pair;
    }

    private function stringifyFloat($float)
    {
        // normalized output among locales

        return rtrim(rtrim(sprintf("%.{$this->precision}F", $float), '0'), '.');
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
    public function jsonSerialize(): \GeoJson\Geometry\Point
    {
        $position = [$this->getLng(), $this->getLat()];
        if ($this->is3d()) $position[] = $this->getAlt();
        return new \GeoJson\Geometry\Point($position);
    }
}
