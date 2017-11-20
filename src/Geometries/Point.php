<?php namespace Phaza\LaravelPostgis\Geometries;

use GeoJson\GeoJson;

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
        return self::stringifyFloat($this->getLng()) . ' ' . self::stringifyFloat($this->getLat());
    }
    
    private static function stringifyFloat($float)
    {
        // normalized output among locales
        return rtrim(rtrim(sprintf('%F', $float), '0'), '.');
    }
    
    public static function fromPair($pair)
    {
        $pair = preg_replace('/^[a-zA-Z\(\)]+/', '', trim($pair));
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
        return $this->toPair();
    }

    /**
     * Convert to GeoJson Point that is jsonable to GeoJSON
     *
     * @return \GeoJson\Geometry\Point
     */
    public function jsonSerialize()
    {
        return new \GeoJson\Geometry\Point([$this->getLng(), $this->getLat()]);
    }
}
