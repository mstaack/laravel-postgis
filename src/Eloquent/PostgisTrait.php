<?php namespace MStaack\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Arr;
use MStaack\LaravelPostgis\Exceptions\PostgisFieldsNotDefinedException;
use MStaack\LaravelPostgis\Exceptions\PostgisTypesMalformedException;
use MStaack\LaravelPostgis\Exceptions\UnsupportedGeomtypeException;
use MStaack\LaravelPostgis\Geometries\Geometry;
use MStaack\LaravelPostgis\Geometries\GeometryInterface;
use MStaack\LaravelPostgis\Schema\Grammars\PostgisGrammar;

trait PostgisTrait
{

    public $geometries = [];
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \MStaack\LaravelPostgis\Eloquent\Builder
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    protected function geogFromText(GeometryInterface $geometry)
    {
        return $this->getConnection()->raw(sprintf("%s.ST_GeogFromText('%s')",
                function_exists('config') ? config('postgis.schema') : 'public', $geometry->toWKT()));
    }

    protected function geomFromText(GeometryInterface $geometry, $srid = 4326)
    {
        return $this->getConnection()->raw(sprintf("%s.ST_GeomFromText('%s', '%d')",
                function_exists('config') ? config('postgis.schema') : 'public', $geometry->toWKT(), $srid));
    }

    public function asWKT(GeometryInterface $geometry, $attrs)
    {
        switch (strtoupper($attrs['geomtype'])) {
            case 'GEOMETRY':
                return $this->geomFromText($geometry, $attrs['srid']);
                break;
            case 'GEOGRAPHY':
            default:
                return $this->geogFromText($geometry);
                break;
        }
    }

    protected function performInsert(EloquentBuilder $query, array $options = [])
    {
        foreach ($this->attributes as $key => $value) {
            if ($value instanceof GeometryInterface) {
                $this->geometries[$key] = $value; //Preserve the geometry objects prior to the insert
                if (! $value instanceof GeometryCollection) {
                    $attrs = $this->getPostgisType($key);
                    $this->attributes[$key] = $this->asWKT($value, $attrs);
                } else {
                    $this->attributes[$key] = $this->geomFromText($value);
                }
            }
        }

        $insert = parent::performInsert($query, $options);

        foreach($this->geometries as $key => $value){
            $this->attributes[$key] = $value; //Retrieve the geometry objects so they can be used in the model
        }

        return $insert; //Return the result of the parent insert
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
        $pgfields = $this->getPostgisFields();

        foreach ($attributes as $attribute => &$value) {
            if (in_array($attribute, $pgfields) && is_string($value) && strlen($value) >= 15) {
                $value = Geometry::fromWKB($value);
            }
        }

        return parent::setRawAttributes($attributes, $sync);
    }

    public function getPostgisType($key)
    {
        $default = [
            'geomtype' => 'geography',
            'srid' => 4326
        ];

        if (property_exists($this, 'postgisTypes')) {
            if (Arr::isAssoc($this->postgisTypes)) {
                if(!array_key_exists($key, $this->postgisTypes)) {
                    return $default;
                }
                $column = $this->postgisTypes[$key];
                if (isset($column['geomtype']) && in_array(strtoupper($column['geomtype']), PostgisGrammar::$allowed_geom_types)) {
                    return $column;
                } else {
                    throw new UnsupportedGeomtypeException('Unsupported GeometryType in $postgisTypes for key ' . $key . ' in ' . __CLASS__);
                }
            } else {
                throw new PostgisTypesMalformedException('$postgisTypes in ' . __CLASS__ . ' has to be an assoc array');
            }
        }

        // Return default geography if postgisTypes does not exist (for backward compatibility)
        return $default;
    }

    public function getPostgisFields()
    {
        if (property_exists($this, 'postgisFields')) {
            return Arr::isAssoc($this->postgisFields) ? //Is the array associative?
                array_keys($this->postgisFields) : //Returns just the keys to preserve compatibility with previous versions
                $this->postgisFields; //Returns the non-associative array that doesn't define the geometry type.
        } else {
            throw new PostgisFieldsNotDefinedException(__CLASS__ . ' has to define $postgisFields');
        }

    }
}
