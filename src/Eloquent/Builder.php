<?php namespace Phaza\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Phaza\LaravelPostgis\Geometries\GeometryInterface;

class Builder extends EloquentBuilder
{
    public function update(array $values)
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $attrs = $this->getPostgisType($key);
                $value = $this->asWKT($value, $attrs);
            }
        }

        return parent::update($values);
    }

    protected function getPostgisFields()
    {
        return $this->getModel()->getPostgisFields();
    }

    protected function getPostgisType($key)
    {
        return $this->getModel()->getPostgisType($key);
    }

    protected function asWKT(GeometryInterface $geometry, $attrs)
    {
        return $this->getModel()->asWKT($geometry, $attrs);
    }
}
