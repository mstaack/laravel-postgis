<?php namespace Phaza\LaravelPostgis\Geometries;

class Point extends Geometry
{
    protected $lat;
    protected $lng;

    public function __construct($lat, $lng)
    {
        $this->lat = (float)$lat;
        $this->lng = (float)$lng;
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

    public function toPair()
    {
        return $this->getLng() . ' ' . $this->getLat();
    }

    public static function fromPair($pair)
    {
        list($lng, $lat) = explode(' ', trim($pair));

        return new static((float)$lat, (float)$lng);
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

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        // !!! This will convert LngLat from PostGIS to ISO 6709 LatLng !!!
        return new \GeoJson\Geometry\Point([$this->getLat(), $this->getLng()]);
    }
}
