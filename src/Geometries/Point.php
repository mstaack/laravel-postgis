<?php namespace Phaza\LaravelPostgis\Geometries;

use GeoJson\GeoJson;

class Point extends Geometry
{
    protected $lat;
    protected $lng;
    protected $alt;
    protected $time;

    public function __construct($lat, $lng, $alt = null, $time = null)
    {
        $this->setLat($lat);
        $this->setLng($lng);
        $this->setAlt($alt);
        $this->setTime($time);
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

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = (float)$time;
    }

    public function toPair()
    {
        return implode(' ', $this->getArray());
    }

    public static function fromPair($pair)
    {
	return new forward_static_call_array('__construct', explode(' ', trim($pair)));
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
        return new \GeoJson\Geometry\Point($this->getArray());
    }

    protected function getArray() 
    {
        $points = [$this->getLng(), $this->getLat()];
        $alt = $this->getAlt();
        if (!is_null($alt)) {
            $points[] = $alt;
            $time = $this->getTime();
            if (!is_null($time)) {
                $points[] = $time;
            }
        }
        return $points;
    }
}
