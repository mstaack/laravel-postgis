<?php namespace MStaack\LaravelPostgis\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use MStaack\LaravelPostgis\Geometries\GeometryInterface;

class Builder extends EloquentBuilder
{
    public function update(array $values)
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                if (is_null($this->model)) {
                    $value = $this->asWKT($value);
                } else {
                    $attrs = $this->model->getPostgisType($key);
                    $value = $this->model->asWKT($value, $attrs);
                }
            }
        }

        return parent::update($values);
    }

    public function upsert(array $values, $uniqueBy, $update = null)
    {
        foreach ($values as &$row) {
            foreach ($row as $column => &$value) {
                if ($value instanceof GeometryInterface) {
                    if (is_null($this->model)) {
                        $value = $this->asWKT($value);
                    } else {
                        $attrs = $this->model->getPostgisType($column);
                        $value = $this->model->asWKT($value, $attrs);
                    }
                }
            }
        }

        return parent::upsert($values, $uniqueBy, $update);
    }

    protected function asWKT(GeometryInterface $geometry)
    {
        return $this->getQuery()->raw(sprintf("%s.ST_GeogFromText('%s')",
                function_exists('config') ? config('postgis.schema') : 'public', $geometry->toWKT()));
    }
}
