<?php namespace Phaza\LaravelPostgis\Geometries;

class Point extends Geometry
{
    protected $lat;
    protected $lng;

    public function __construct($lat, $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    public function toPair()
    {
        return $this->getLng() . ' ' . $this->getLat();
    }

    public static function fromPair($pair)
    {
        list($lng, $lat) = explode(' ', trim($pair));

        return new static($lat, $lng);
    }

    public function toWKT()
    {
        return sprintf('POINT(%s)', (string)$this);
    }

    public static function fromString($wktArgument)
    {
        return static::fromPair($wktArgument);
    }

    public function __toString()
    {
        return $this->getLng() . ' ' . $this->getLat();
    }
}
