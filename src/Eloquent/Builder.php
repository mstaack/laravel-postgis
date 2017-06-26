<?php namespace Phaza\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Phaza\LaravelPostgis\Geometries\GeometryInterface;

class Builder extends EloquentBuilder
{
    public function update(array $values)
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $value = $this->asWKT($value);
            }
        }

        return parent::update($values);
    }

    public function firstOrCreate(array $attributes, array $values = Array() )
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $value = $this->asWKT($value);
            }
        }

        return parent::firstOrCreate($values);
    }

    protected function getPostgisFields()
    {
        return $this->getModel()->getPostgisFields();
    }


    protected function asWKT(GeometryInterface $geometry)
    {
        return $this->getQuery()->raw(sprintf("public.ST_GeogFromText('%s')", $geometry->toWKT()));
    }
}
